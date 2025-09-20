#!/bin/bash

echo "🛠️ WebSec Labs - Modo Desenvolvimento"
echo

echo "⏹️ Parando containers..."
docker-compose down

echo "🔄 Reiniciando apenas containers (sem rebuild)..."
docker-compose up -d

echo "⏳ Aguardando inicialização..."
sleep 3

echo
echo "✅ Modo desenvolvimento ativo!"
echo
echo "📝 Agora você pode editar arquivos PHP sem rebuild:"
echo "- router/ → código do roteador"
echo "- src/ → código da aplicação principal"
echo
echo "📊 URLs para testar:"
echo "- Roteador: http://localhost:8181"
echo "- App Principal: http://localhost"
echo "- Status: http://localhost:8181/status.php"
echo "- Reset API: http://localhost:8181/reset_api.php"
echo
echo "🔄 Para aplicar mudanças: apenas salve o arquivo!"