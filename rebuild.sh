#!/bin/bash

echo "🔄 Rebuild do WebSec Labs - CSRF/SSRF Demo"
echo

echo "⏹️ Parando containers..."
docker-compose down

echo "🏗️ Rebuild do container router..."
docker-compose build --no-cache router

echo "🚀 Iniciando containers..."
docker-compose up -d

echo
echo "✅ Rebuild concluído!"
echo
echo "📊 URLs para testar:"
echo "- Status JSON: http://localhost:8181/api_status.php"
echo "- Status Visual: http://localhost:8181/status.php"
echo "- Ataque CSRF: http://localhost/?page=csrf-ssrf-demo"
echo
echo "🧪 Teste com: curl http://localhost:8181/api_status.php"