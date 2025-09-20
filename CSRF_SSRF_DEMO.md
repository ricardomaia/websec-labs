# CSRF/SSRF Attack Demonstration

## 📋 Visão Geral

Esta demonstração educacional simula um ataque onde uma página maliciosa na internet acessa e compromete um dispositivo na rede interna da vítima (como um roteador Wi-Fi) usando credenciais padrão.

## 🎯 Cenário do Ataque

### Dispositivo Alvo
- **Tipo**: Roteador Wi-Fi simulado (WifiRouter Pro X500)
- **IP Interno**: Acessível via `http://router:80` ou `http://localhost:8181`
- **Credenciais**: `admin/admin` (padrão de fábrica)
- **Vulnerabilidades**:
  - Credenciais padrão não alteradas
  - Falta de proteção CSRF
  - Execução de comandos via GET

### Página Maliciosa
- **Localização**: `http://localhost/?page=csrf-ssrf-demo`
- **Funcionalidade**: Executa ataques automatizados contra dispositivos internos
- **Técnicas**: CSRF, SSRF, Login automático, Formulários invisíveis

## 🚀 Como Executar a Demonstração

### 1. Iniciar os Containers
```bash
docker-compose up -d
```

### 2. Acessar os Serviços
- **Aplicação Principal**: http://localhost
- **Roteador Simulado**: http://localhost:8181
- **Página Maliciosa**: http://localhost/?page=csrf-ssrf-demo

### 3. Executar o Ataque
1. Abra a página maliciosa em uma aba
2. Abra o roteador em outra aba para verificar as configurações
3. Na página maliciosa, clique em "🚀 Iniciar Ataque"
4. Observe os logs no console de desenvolvedor (F12)
5. Verifique as alterações no roteador

## 🔍 O Que Acontece Durante o Ataque

### Fase 1: Descoberta
- Varredura de IPs internos comuns (192.168.1.1, 192.168.0.1, etc.)
- Tentativa de acesso ao painel de administração

### Fase 2: Exploração
- Teste de credenciais padrão comuns
- Login automático via formulários CSRF

### Fase 3: Comprometimento
- Alteração das configurações de DNS para servidor malicioso
- Execução de comandos remotos via parâmetros GET
- Possível reset de fábrica ou reinicialização

## 📊 Logs e Monitoramento

### Console do Navegador
Todos os passos do ataque são logados no console:
```javascript
[CSRF/SSRF Attack] 🎯 Iniciando varredura de rede interna...
[CSRF/SSRF Attack] 🔍 Tentando acessar: http://router
[CSRF/SSRF Attack] 🔓 Tentando login automático em: http://router
[CSRF/SSRF Attack] 🔑 Testando credenciais: admin/admin
[CSRF/SSRF Attack] ✅ Login bem-sucedido em: http://router
[CSRF/SSRF Attack] 🌐 Alterando DNS primário para: 1.1.1.1
[CSRF/SSRF Attack] ⚡ Executando comando: reboot
```

### Logs do Roteador
O roteador também gera logs visíveis no console:
```javascript
🔐 WifiRouter Pro - Painel de Login
🔍 Tentativa de login detectada
🌐 Alteração de DNS solicitada
🔄 Reinicialização do roteador solicitada
```

## 🛡️ Técnicas de Prevenção

### Para Usuários
1. **Alterar credenciais padrão** imediatamente após instalação
2. **Atualizar firmware** regularmente
3. **Desabilitar WPS** e recursos desnecessários
4. **Usar redes separadas** para IoT
5. **Monitorar dispositivos** da rede

### Para Desenvolvedores
1. **Implementar tokens CSRF** em todos os formulários
2. **Validar origem** das requisições (CORS adequado)
3. **Usar HTTPS** sempre que possível
4. **Implementar rate limiting** para tentativas de login
5. **Logs de auditoria** para todas as ações administrativas
6. **Autenticação multifator** quando aplicável

## 🏗️ Arquitetura da Demonstração

### Containers Docker
```yaml
services:
  php:          # Aplicação principal (porta 80)
  router:       # Roteador simulado (porta 8181)
  db:           # Banco MySQL (porta 3306)
  adminer:      # Interface DB (porta 8080)
```

### Arquivos Principais
```
src/
├── csrf_ssrf_demo.php    # Página maliciosa
router/
├── index.php             # Login do roteador
└── admin.php             # Painel administrativo
Dockerfile.router         # Container do roteador
```

## ⚠️ Avisos Importantes

- **Uso Educacional Apenas**: Esta demonstração é para fins de aprendizado
- **Não Use em Produção**: Contém vulnerabilidades intencionais
- **Ambiente Isolado**: Execute apenas em ambiente controlado
- **Responsabilidade Legal**: Use apenas em sistemas próprios ou autorizados

## 🔧 Personalização

### Adicionando Novos Dispositivos
Para simular outros dispositivos IoT, adicione novos containers no `docker-compose.yaml`:

```yaml
camera:
  build:
    context: .
    dockerfile: Dockerfile.camera
  ports:
    - "8282:80"
```

### Modificando Credenciais
Edite as credenciais em `router/index.php`:
```php
$valid_username = 'admin';
$valid_password = 'admin';
```

### Customizando Ataques
Modifique a lógica de ataque em `csrf_ssrf_demo.php` na função `performMaliciousActions()`.

## 📚 Recursos Adicionais

- [OWASP CSRF Prevention](https://owasp.org/www-community/attacks/csrf)
- [OWASP SSRF Prevention](https://owasp.org/Top10/A10_2021-Server-Side_Request_Forgery_%28SSRF%29/)
- [IoT Security Guidelines](https://www.owasp.org/index.php/IoT_Security_Guidance)

---

**Lembre-se**: Esta demonstração é puramente educacional. Sempre obtenha autorização adequada antes de testar técnicas de segurança em qualquer sistema.