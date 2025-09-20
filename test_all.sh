#!/bin/bash

echo "🧪 Teste Completo - WebSec Labs"
echo "================================"
echo

# Função para verificar resposta HTTP
check_url() {
    local url=$1
    local expected=$2
    local description=$3

    echo -n "Testando $description... "

    response=$(curl -s -o /dev/null -w "%{http_code}" "$url")

    if [ "$response" = "$expected" ]; then
        echo "✅ OK ($response)"
        return 0
    else
        echo "❌ FALHOU ($response, esperado $expected)"
        return 1
    fi
}

# Função para verificar JSON
check_json() {
    local url=$1
    local key=$2
    local expected=$3
    local description=$4

    echo -n "Testando $description... "

    response=$(curl -s "$url" | jq -r ".$key" 2>/dev/null)

    if [ "$response" = "$expected" ]; then
        echo "✅ OK ($key: $response)"
        return 0
    else
        echo "❌ FALHOU ($key: $response, esperado $expected)"
        return 1
    fi
}

echo "📊 1. Verificando Serviços Básicos"
echo "-----------------------------------"

check_url "http://localhost" "200" "App Principal"
check_url "http://localhost:8181" "200" "Roteador"
check_url "http://localhost:8181/status.php" "200" "Status do Roteador"
check_url "http://localhost:8181/api_status.php" "200" "API Status"
check_url "http://localhost:8181/reset_api.php" "200" "API Reset"

echo
echo "🔧 2. Testando APIs"
echo "-------------------"

check_json "http://localhost:8181/api_status.php" "dns_config.primary_dns" "8.8.8.8" "DNS Padrão"

echo
echo "🎯 3. Testando Ataque CSRF"
echo "---------------------------"

echo -n "Executando ataque CSRF... "
attack_result=$(curl -s -X POST http://localhost:8181/admin.php \
  -d "primary_dns=99.99.99.99" \
  -d "secondary_dns=99.99.99.98" \
  -w "%{http_code}")

if [[ $attack_result == *"200"* ]]; then
    echo "✅ Ataque enviado"

    sleep 2

    check_json "http://localhost:8181/api_status.php" "dns_config.primary_dns" "99.99.99.99" "DNS Comprometido"
    check_json "http://localhost:8181/api_status.php" "status" "COMPROMISED" "Status Comprometido"
else
    echo "❌ FALHOU (HTTP: $attack_result)"
fi

echo
echo "🔄 4. Testando Reset"
echo "--------------------"

echo -n "Executando reset via API... "
reset_result=$(curl -s "http://localhost:8181/reset_api.php?action=reset" | jq -r ".success" 2>/dev/null)

if [ "$reset_result" = "true" ]; then
    echo "✅ Reset executado"

    sleep 2

    check_json "http://localhost:8181/api_status.php" "dns_config.primary_dns" "8.8.8.8" "DNS Restaurado"
    check_json "http://localhost:8181/api_status.php" "status" "NORMAL" "Status Normal"
else
    echo "❌ FALHOU (success: $reset_result)"
fi

echo
echo "📝 5. Verificando Logs"
echo "----------------------"

echo -n "Verificando logs de atividade... "
log_count=$(curl -s "http://localhost:8181/api_status.php" | jq -r ".attack_logs | length" 2>/dev/null)

if [ "$log_count" -gt 0 ]; then
    echo "✅ Logs encontrados ($log_count entradas)"
else
    echo "⚠️ Sem logs de ataque"
fi

echo
echo "🎯 Teste Completo Finalizado!"
echo "=============================="
echo

# Resumo final
echo "📋 URLs para verificação manual:"
echo "- App Principal: http://localhost"
echo "- Roteador: http://localhost:8181"
echo "- Status: http://localhost:8181/status.php"
echo "- CSRF Demo: http://localhost/?page=csrf-ssrf-demo"
echo "- CSRF Realista: http://localhost/?page=csrf-realistic-demo"
echo

echo "🔧 Comandos úteis:"
echo "- Reset: curl http://localhost:8181/reset_api.php?action=reset"
echo "- Status: curl http://localhost:8181/api_status.php"
echo "- Logs: docker-compose logs -f router"
echo