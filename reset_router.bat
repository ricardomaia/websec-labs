@echo off
echo 🔄 Reset do Roteador - WebSec Labs
echo.

echo 🗑️ Removendo dados persistentes...
if exist "router_data\dns_config.json" del "router_data\dns_config.json"
if exist "router_data\activity_log.json" del "router_data\activity_log.json"

echo 🔄 Reiniciando container do roteador...
docker-compose restart router

echo ⏳ Aguardando inicialização...
timeout /t 3 > nul

echo.
echo ✅ Reset concluído!
echo 📊 Verificar: http://localhost:8181/status.php
echo 🎯 DNS deve estar: 8.8.8.8 / 8.8.4.4
echo.
pause