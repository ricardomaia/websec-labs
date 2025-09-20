# 🔄 Como Resetar o Roteador

## ❓ **Problema:**
As configurações de DNS permanecem alteradas após restart do container devido à persistência em volume Docker.

## ✅ **Soluções Disponíveis:**

### **1. 🖱️ Via Interface Web (Mais Fácil)**

#### **Painel Administrativo:**
- Acesse: http://localhost:8181/admin.php
- Faça login: `admin/admin`
- Clique no botão **"🏭 Executar Reset de Fábrica"**

#### **Página Maliciosa:**
- Acesse: http://localhost/?page=csrf-ssrf-demo
- Clique no botão **"🏭 Reset Roteador"**

### **2. 🛠️ Via Scripts (Automático)**

#### **Windows:**
```cmd
reset_router.bat
```

#### **Linux/Mac:**
```bash
chmod +x reset_router.sh
./reset_router.sh
```

### **3. 📡 Via API (Programático)**

```bash
# GET Request para reset
curl http://localhost:8181/reset_api.php?action=reset

# POST Request para reset
curl -X POST http://localhost:8181/reset_api.php
```

### **4. 💻 Via Linha de Comando (Manual)**

```bash
# Opção 1: Remover arquivos de dados
rm router_data/dns_config.json
rm router_data/activity_log.json
docker-compose restart router

# Opção 2: Rebuild completo
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## 🔍 **Como Verificar se Reset Funcionou:**

### **Status Visual:**
- **URL**: http://localhost:8181/status.php
- **Esperado**: Fundo verde, DNS: 8.8.8.8/8.8.4.4

### **API JSON:**
- **URL**: http://localhost:8181/api_status.php
- **Esperado**: `"status": "NORMAL"`

### **Linha de Comando:**
```bash
# Verificar arquivos de dados
cat router_data/dns_config.json

# Deve mostrar:
{
    "primary_dns": "8.8.8.8",
    "secondary_dns": "8.8.4.4",
    "last_changed": "Configuração de fábrica",
    "changed_by": "Sistema"
}
```

## 📋 **Estados do Roteador:**

### **✅ Estado Normal (Após Reset):**
```json
{
    "primary_dns": "8.8.8.8",
    "secondary_dns": "8.8.4.4",
    "last_changed": "Configuração de fábrica",
    "changed_by": "Sistema"
}
```

### **🚨 Estado Comprometido (Após Ataque):**
```json
{
    "primary_dns": "66.66.66.66",
    "secondary_dns": "66.66.66.67",
    "last_changed": "2024-01-15 14:30:22",
    "changed_by": "Ataque CSRF (não logado)"
}
```

## 🔄 **Fluxo de Teste Completo:**

```bash
# 1. Verificar estado inicial
curl http://localhost:8181/api_status.php

# 2. Executar ataque
# Vá para: http://localhost/?page=csrf-ssrf-demo
# Clique: "🚀 Iniciar Ataque"

# 3. Verificar comprometimento
curl http://localhost:8181/api_status.php
# Deve mostrar: "status": "COMPROMISED"

# 4. Resetar
curl http://localhost:8181/reset_api.php?action=reset

# 5. Verificar reset
curl http://localhost:8181/api_status.php
# Deve mostrar: "status": "NORMAL"
```

## ⚠️ **Notas Importantes:**

1. **Persistência é Intencional**: Para demonstrar impacto real do ataque
2. **Volume Docker**: Dados ficam em `./router_data/`
3. **Reset Não Afeta**: Logs do Apache ou outros containers
4. **Restart vs Reset**: Restart mantém dados, reset limpa tudo

## 🛟 **Troubleshooting:**

### **Reset não funciona via API:**
```bash
# Verificar se API existe
curl -I http://localhost:8181/reset_api.php

# Reset manual via linha de comando
rm router_data/*.json
docker-compose restart router
```

### **DNS não volta ao padrão:**
```bash
# Verificar se arquivos existem
ls -la router_data/

# Remover forçadamente
sudo rm -rf router_data/*
docker-compose restart router
```

### **Container não reinicia:**
```bash
# Rebuild completo
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

---

**Agora você tem múltiplas maneiras de resetar o roteador! 🎯**