#!/bin/bash

echo "🔄 Reset do Roteador - WebSec Labs"
echo

echo "🗑️ Removendo dados persistentes..."
rm -f router_data/dns_config.json
rm -f router_data/activity_log.json

echo "🔄 Reiniciando container do roteador..."
docker-compose restart router

echo "⏳ Aguardando inicialização..."
sleep 3

echo
echo "✅ Reset concluído!"
echo "📊 Verificar: http://localhost:8181/status.php"
echo "🎯 DNS deve estar: 8.8.8.8 / 8.8.4.4"
echo