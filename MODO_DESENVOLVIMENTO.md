# 🛠️ Modo Desenvolvimento - WebSec Labs

## ✅ **Problema Resolvido**

Agora você **NÃO precisa mais rebuildar** a imagem Docker a cada mudança no código PHP!

## 🔄 **Modificações Implementadas**

### **Docker-Compose Atualizado:**
```yaml
router:
  volumes:
    - ./log:/var/log/apache2
    - ./router_data:/var/www/html/data     # Dados persistentes
    - ./router:/var/www/html               # 🆕 Código fonte mapeado
```

### **Benefícios:**
- ✅ **Edite arquivos PHP** → Mudanças aplicadas instantaneamente
- ✅ **Sem rebuild** necessário
- ✅ **Desenvolvimento mais rápido**
- ✅ **Debug em tempo real**

## 🚀 **Como Usar**

### **Primeira Vez (Setup):**
```bash
# Windows
dev_mode.bat

# Linux/Mac
chmod +x dev_mode.sh
./dev_mode.sh
```

### **Uso Diário:**
```bash
# Para aplicar docker-compose atualizado
docker-compose down
docker-compose up -d

# Agora edite qualquer arquivo em:
# - router/ (código do roteador)
# - src/ (aplicação principal)
```

## 📁 **Estrutura de Volumes**

```
websec-labs/
├── router/                    → /var/www/html (no container)
│   ├── admin.php             → Editável sem rebuild
│   ├── admin_realistic.php   → Editável sem rebuild
│   ├── index.php             → Editável sem rebuild
│   ├── status.php            → Editável sem rebuild
│   └── reset_api.php         → Editável sem rebuild
├── router_data/              → /var/www/html/data
│   ├── dns_config.json       → Dados persistentes
│   └── activity_log.json     → Logs de atividade
└── src/                      → Aplicação principal
    ├── csrf_ssrf_demo.php    → Editável sem rebuild
    └── csrf_realistic_demo.php → Editável sem rebuild
```

## 🧪 **Teste do Reset via Interface**

### **Passos para Testar:**

```bash
# 1. Ativar modo desenvolvimento
docker-compose down
docker-compose up -d

# 2. Verificar estado atual
curl http://localhost:8181/api_status.php

# 3. Comprometer (se necessário)
curl -X POST http://localhost:8181/admin.php \
  -d "primary_dns=66.66.66.66" \
  -d "secondary_dns=66.66.66.67"

# 4. Testar reset via interface
# Acesse: http://localhost:8181/admin.php
# Clique: "Executar Reset de Fábrica"

# 5. Verificar se funcionou
curl http://localhost:8181/api_status.php
```

### **Debug do Reset:**

Se o reset via interface não funcionar, verifique os logs:

```bash
# Logs do Apache (onde aparecem os error_log)
docker-compose logs router

# Ou logs em tempo real
docker-compose logs -f router
```

## 🔧 **Troubleshooting**

### **Código não atualiza:**
```bash
# Verificar se volume está mapeado
docker inspect router | grep Mounts -A 20

# Deve mostrar:
# "Source": "/path/to/websec-labs/router"
# "Destination": "/var/www/html"
```

### **Reset não funciona:**
```bash
# Verificar logs em tempo real
docker-compose logs -f router

# Testar reset via API (sempre funciona)
curl http://localhost:8181/reset_api.php?action=reset
```

### **Permissões (Linux/Mac):**
```bash
# Se houver problemas de permissão
sudo chown -R $USER:$USER router_data/
chmod 755 router_data/
```

## 📊 **Comparação: Antes vs Depois**

| Ação | Antes | Depois |
|------|-------|--------|
| **Editar PHP** | Rebuild + Restart | Apenas salvar arquivo |
| **Testar mudança** | 2-3 minutos | Instantâneo |
| **Debug** | Difícil | Fácil (logs em tempo real) |
| **Desenvolvimento** | Lento | Rápido |

## 🎯 **Workflows Comuns**

### **Desenvolvimento de Nova Feature:**
```bash
# 1. Edite arquivos em router/ ou src/
vim router/admin.php

# 2. Salve o arquivo
# 3. Teste imediatamente
curl http://localhost:8181/admin.php

# 4. Ver logs se necessário
docker-compose logs -f router
```

### **Debug de Problema:**
```bash
# 1. Adicione error_log() no código
error_log("DEBUG: minha variável = " . $minha_var);

# 2. Salve o arquivo
# 3. Execute a ação
# 4. Veja logs em tempo real
docker-compose logs -f router
```

### **Reset entre Testes:**
```bash
# Método 1: Via API (rápido)
curl http://localhost:8181/reset_api.php?action=reset

# Método 2: Via interface (teste manual)
# http://localhost:8181/admin.php → botão reset

# Método 3: Via script
./reset_router.sh
```

## ✅ **Benefícios Finais**

- 🚀 **Desenvolvimento 10x mais rápido**
- 🔄 **Sem rebuilds desnecessários**
- 🐛 **Debug em tempo real**
- 📝 **Edição instantânea**
- 🧪 **Testes mais ágeis**

**Agora você pode desenvolver como um profissional! 🎯**