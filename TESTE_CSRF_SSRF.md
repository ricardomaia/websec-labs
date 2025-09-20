# Guia de Teste - CSRF/SSRF Attack Demo

## 🚀 Passos para Testar a Demonstração

### 1. Iniciar o Ambiente
```bash
docker-compose up -d
```

### 2. Verificar Serviços Ativos
- **App Principal**: http://localhost (porta 80)
- **Roteador Simulado**: http://localhost:8181 (porta 8181)
- **Status do Roteador**: http://localhost:8181/status.php (página de monitoramento)
- **Teste Manual**: http://localhost:8181/test_attack.html (teste do ataque)
- **Adminer**: http://localhost:8080 (porta 8080)

### 3. Executar o Teste

#### Passo 1: Acessar o Roteador (Estado Inicial)
1. Abra http://localhost:8181
2. Faça login com: `admin/admin`
3. Observe as configurações de DNS padrão:
   - DNS Primário: `8.8.8.8`
   - DNS Secundário: `8.8.4.4`
   - Último alterado: "Configuração de fábrica"

#### Passo 2: Abrir a Página Maliciosa
1. Abra uma nova aba: http://localhost/?page=csrf-ssrf-demo
2. Abra o Console de Desenvolvedor (F12)
3. **IMPORTANTE**: Mantenha ambas as abas abertas

#### Passo 3: Executar o Ataque
1. Na página maliciosa, clique em "🚀 Iniciar Ataque"
2. Observe os logs no console e na interface
3. Aguarde até ver "🏁 Ataque concluído!"

#### Passo 4: Verificar Comprometimento
1. Volte para a aba do roteador (http://localhost:8181/admin.php)
2. Recarregue a página ou navegue até "Configurações de DNS"
3. **Você deve ver**:
   - DNS Primário alterado para: `66.66.66.66`
   - DNS Secundário alterado para: `66.66.66.67`
   - Última alteração com timestamp recente
   - No log de atividades: entrada "Comando executado via GET" marcada em vermelho

#### Passo 5: Verificar Status em Tempo Real
1. Abra http://localhost:8181/status.php em uma nova aba
2. **Esta página atualiza automaticamente a cada 5 segundos**
3. **Você deve ver**:
   - DNS alterado para 66.66.66.66/67 (fundo vermelho = comprometido)
   - Logs de atividade mostrando "Ataque CSRF (não logado)"
   - Arquivos JSON criados com timestamps

#### Passo 6: Testar Persistência
1. Faça logout do roteador e login novamente
2. Ou feche e abra uma nova aba para http://localhost:8181
3. **As configurações DNS alteradas devem continuar lá**
4. **O log de atividades deve mostrar todo o histórico do ataque**

## 📊 O Que Você Deve Observar

### No Console da Página Maliciosa
```
[CSRF/SSRF Attack] 🎯 Iniciando varredura de rede interna...
[CSRF/SSRF Attack] 🔍 Tentando acessar: http://localhost:8181
[CSRF/SSRF Attack] 🔓 Tentando login automático em: http://localhost:8181
[CSRF/SSRF Attack] 🔑 Testando credenciais: admin/admin
[CSRF/SSRF Attack] 🎉 SUCESSO! Credenciais válidas encontradas: admin/admin
[CSRF/SSRF Attack] 🌐 Alterando DNS primário para: 66.66.66.66
[CSRF/SSRF Attack] ✅ DNS alterado com sucesso!
[CSRF/SSRF Attack] 📡 DNS Primário alterado para: 66.66.66.66
[CSRF/SSRF Attack] ⚡ Executando comando: reboot
[CSRF/SSRF Attack] 🏁 Ataque concluído! Dispositivo comprometido.
```

### Na Interface da Página Maliciosa
- Status muda para "🚨 DISPOSITIVO COMPROMETIDO!"
- Mostra DNS malicioso configurado
- Botão para verificar alterações no roteador

### No Roteador Comprometido
- **Configuração Atual** mostra DNS malicioso (66.66.66.66/67)
- **Log de Atividades** mostra:
  - Login realizado
  - Configuração DNS alterada
  - Comandos executados via GET (marcados em vermelho)
- **Persistência**: Configurações são salvas em arquivo e mantidas mesmo após logout/login

## 🔍 Detalhes Técnicos do Ataque

### Como Funciona
1. **Descoberta**: JavaScript tenta acessar localhost:8181 e IPs internos comuns
2. **Login CSRF**: Formulários invisíveis enviam credenciais padrão (admin/admin)
3. **Exploração**: Altera configurações via POST e executa comandos via GET
4. **Persistência**: Configurações ficam salvas em arquivos JSON no container

### Vulnerabilidades Exploradas
- ✅ Credenciais padrão não alteradas
- ✅ Falta de proteção CSRF
- ✅ Execução de comandos via GET
- ✅ Ausência de validação de origem
- ✅ Sem rate limiting

## 🛡️ Como Prevenir

### Para Usuários
- Alterar credenciais padrão imediatamente
- Manter firmware atualizado
- Usar redes separadas para IoT
- Monitorar logs de acesso

### Para Desenvolvedores
- Implementar tokens CSRF
- Validar origem das requisições (CORS)
- Usar HTTPS obrigatório
- Implementar rate limiting
- Logs de auditoria detalhados

## 🔄 Reset do Teste

Para refazer o teste:
1. Reinicie os containers: `docker-compose restart`
2. Ou use o botão "🧹 Limpar Logs" na página maliciosa
3. Faça logout e login novamente no roteador

## ⚠️ Troubleshooting

### Problema: DNS não muda
- Verifique se você está logado no roteador
- Certifique-se de que ambas as abas estão abertas
- Recarregue a página do roteador após o ataque

### Problema: Console não mostra logs
- Abra as ferramentas de desenvolvedor (F12)
- Vá para a aba "Console"
- Clique em "Iniciar Ataque" novamente

### Problema: Containers não iniciam
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## 📚 Objetivos Educacionais

Este teste demonstra:
- Como ataques CSRF funcionam na prática
- Riscos de manter credenciais padrão
- Importância de logs de segurança
- Necessidade de proteções adequadas
- Impacto real de vulnerabilidades simples

**Lembre-se**: Use apenas para fins educacionais!