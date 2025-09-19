# 🔐 WebSec Labs - Credenciais de Teste

⚠️ **ATENÇÃO: Estas são credenciais de teste para fins educacionais apenas!**

Este documento contém todas as credenciais e informações de acesso para o ambiente de laboratório de segurança web.

## 📋 Resumo das Credenciais

### Usuários da Aplicação

| Username | Password | Função | Email | Observações |
|----------|----------|---------|-------|-------------|
| `admin` | `password123` | Administrador | admin@webseclabs.local | Conta administrativa para testes de privilégios |
| `foo` | `test` | Usuário comum | foo_21@webseclabs.local | Conta de usuário regular |
| `bar` | `admin` | Usuário comum | b.bar@webseclabs.local | Usuário com senha fraca |

### Credenciais do Banco de Dados

| Serviço | Host | Porta | Usuário | Senha | Database |
|---------|------|-------|---------|--------|----------|
| MySQL | localhost/db | 3306 | root | root | websec_labs |

### Acesso aos Serviços

| Serviço | URL | Porta | Observações |
|---------|-----|-------|-------------|
| Aplicação Web | http://localhost | 80 | Aplicação principal |
| Adminer (DB Admin) | http://localhost:8080 | 8080 | Interface web para MySQL |

## 🎯 Cenários de Teste

### 1. SQL Injection
- **URL**: `http://localhost/?page=sql-injection`
- **Usuário**: Qualquer um
- **Payloads de exemplo**:
  - `?page=sql-injection&id=1' OR '1'='1`
  - `?page=sql-injection&id=1 UNION SELECT 1,username,password,email FROM user--`

### 2. Autenticação e Sessões
- **Teste de força bruta**: Use as credenciais acima
- **Bypass de autenticação**: Teste SQL injection no login
- **Escalação de privilégios**: Login como `foo` ou `bar`, tente acessar funções de `admin`

### 3. File Inclusion
- **URL**: `http://localhost/?page=file-inclusion`
- **Payloads de teste**:
  - `../../../etc/passwd` (Path Traversal)
  - `php://filter/convert.base64-encode/resource=index.php` (PHP Filters)

### 4. Clickjacking
- **URL**: `http://localhost/?page=clickjacking`
- **Teste**: Verificar headers X-Frame-Options

## 🛠️ Hashes MD5 das Senhas

Para referência técnica, aqui estão os hashes MD5 utilizados:

```
admin:password123 → 482c811da5d5b4bc6d497ffa98491e38
foo:test         → 098f6bcd4621d373cade4e832627b4f6
bar:admin        → 21232f297a57a5a743894a0e4a801fc3
```

## 🔍 Comandos Úteis para Testes

### Acessar MySQL via linha de comando:
```bash
docker exec -it db mysql -u root -proot websec_labs
```

### Ver logs do Apache:
```bash
docker logs php_container
```

### Reiniciar serviços:
```bash
docker-compose restart
```

## 📊 Estrutura do Banco de Dados

### Tabela `user`
- `id` (int): ID único do usuário
- `username` (varchar): Nome de usuário
- `password` (varchar): Hash MD5 da senha
- `email` (varchar): Email do usuário

### Tabela `product`
- `id` (int): ID do produto
- `name` (varchar): Nome do produto
- `description` (text): Descrição
- `price` (decimal): Preço
- `category` (varchar): Categoria
- `stock` (int): Estoque

### Tabela `comments`
- `id` (int): ID do comentário
- `user_id` (int): FK para usuário
- `product_id` (int): FK para produto
- `comment` (text): Comentário (potencial XSS)
- `rating` (int): Avaliação 1-5

### Tabela `admin_settings`
- `id` (int): ID da configuração
- `setting_name` (varchar): Nome da configuração
- `setting_value` (text): Valor
- `description` (varchar): Descrição
- `modified_by` (int): FK para usuário

## ⚠️ Avisos de Segurança

1. **Não use em produção**: Este ambiente contém vulnerabilidades intencionais
2. **Isolamento**: Execute apenas em ambiente controlado/isolado
3. **Finalidade educacional**: Para aprendizado de segurança apenas
4. **Monitoramento**: Monitore o tráfego de rede ao executar

## 🎓 Exercícios Sugeridos

1. **SQL Injection**: Extrair todos os hashes de senha
2. **XSS**: Inserir scripts maliciosos nos comentários
3. **Authentication Bypass**: Fazer login sem conhecer a senha
4. **Privilege Escalation**: Acessar funções administrativas
5. **File Inclusion**: Ler arquivos do sistema
6. **Session Management**: Sequestro de sessão

---

**Última atualização**: $(date)
**Versão**: 2.0
**Mantido por**: WebSec Labs Team