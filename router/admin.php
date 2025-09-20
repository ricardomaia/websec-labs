<?php
// Headers CORS para demonstração educacional
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Verifica se está logado (com exceção para demonstração de CSRF)
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

// Para demonstração educacional: permite alterações DNS via POST mesmo sem login
if (!$is_logged_in && !($_POST['primary_dns'] ?? false)) {
    header('Location: index.php');
    exit;
}

$success = '';
$error = '';

// Definir arquivos de persistência
$config_file = '/var/www/html/data/dns_config.json';
$log_file = '/var/www/html/data/activity_log.json';

// Criar diretório de dados se não existir
$data_dir = dirname($config_file);
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0777, true);
}

// Funções auxiliares para persistência
function loadDNSConfig($file)
{
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: getDefaultDNSConfig();
    }
    return getDefaultDNSConfig();
}

function saveDNSConfig($file, $config)
{
    file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT));
}

function loadActivityLog($file)
{
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function saveActivityLog($file, $log)
{
    file_put_contents($file, json_encode($log, JSON_PRETTY_PRINT));
}

function addActivityLog($file, $activity)
{
    $log = loadActivityLog($file);
    $log[] = $activity;
    // Manter apenas os últimos 50 registros
    if (count($log) > 50) {
        $log = array_slice($log, -50);
    }
    saveActivityLog($file, $log);
}

function getDefaultDNSConfig()
{
    return [
        'primary_dns' => '8.8.8.8',
        'secondary_dns' => '8.8.4.4',
        'last_changed' => 'Configuração de fábrica',
        'changed_by' => 'Sistema'
    ];
}

// Carrega configurações dos arquivos
$dns_config = loadDNSConfig($config_file);
$activity_log = loadActivityLog($log_file);

// DEBUG: Log what we loaded
error_log("DEBUG: Config file: " . $config_file);
error_log("DEBUG: DNS config loaded: " . json_encode($dns_config));
error_log("DEBUG: File exists: " . (file_exists($config_file) ? 'YES' : 'NO'));

// Inicializa log se estiver vazio
if (empty($activity_log)) {
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Sistema iniciado',
        'user' => 'Sistema',
        'details' => 'Configurações padrão carregadas'
    ]);
    $activity_log = loadActivityLog($log_file);
}

// DEBUG: Log incoming POST data
error_log("DEBUG: POST data received: " . json_encode($_POST));
error_log("DEBUG: Session data: " . json_encode($_SESSION));
error_log("DEBUG: Reset config param: " . ($_POST['reset_config'] ?? 'NOT SET'));

// Processa alteração de DNS
if ($_POST['primary_dns'] ?? false) {
    $primary_dns = $_POST['primary_dns'];
    $secondary_dns = $_POST['secondary_dns'] ?? '';

    error_log("DEBUG: Processing DNS change: Primary=$primary_dns, Secondary=$secondary_dns");

    // Salva as configurações no arquivo
    $changed_by = 'Ataque CSRF (não logado)';
    if ($is_logged_in) {
        $changed_by = $_SESSION['username'] ?? 'admin';
    }

    $dns_config = [
        'primary_dns' => $primary_dns,
        'secondary_dns' => $secondary_dns,
        'last_changed' => date('Y-m-d H:i:s'),
        'changed_by' => $changed_by
    ];
    saveDNSConfig($config_file, $dns_config);

    // DEBUG: Confirm save
    error_log("DEBUG: Saved DNS config: " . json_encode($dns_config));
    error_log("DEBUG: Config file after save: " . $config_file);

    // Recarrega as configurações para garantir que estão atualizadas
    $dns_config = loadDNSConfig($config_file);
    error_log("DEBUG: Reloaded DNS config after save: " . json_encode($dns_config));

    // Adiciona ao log de atividades
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Configuração DNS alterada',
        'user' => $changed_by,
        'details' => "DNS: {$primary_dns} / {$secondary_dns}"
    ]);

    // Simula salvamento das configurações
    $success = "Configurações de DNS atualizadas com sucesso!<br>";
    $success .= "DNS Primário: " . htmlspecialchars($primary_dns) . "<br>";
    if ($secondary_dns) {
        $success .= "DNS Secundário: " . htmlspecialchars($secondary_dns);
    }

    // Log para demonstração
    error_log("DNS Change: Primary={$primary_dns}, Secondary={$secondary_dns}");
}

// Processa comando personalizado via GET (vulnerabilidade intencional)
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    $success = "Comando executado: " . htmlspecialchars($cmd);

    // Adiciona ao log de atividades
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Comando executado via GET',
        'user' => 'Remoto (possível ataque)',
        'details' => "Comando: {$cmd}"
    ]);

    error_log("Command executed via GET: {$cmd}");
}

// Processa reset de configurações
error_log("DEBUG: Checking reset condition: " . ($_POST['reset_config'] ?? 'false'));
if (isset($_POST['reset_config']) && $_POST['reset_config'] == '1') {
    error_log("DEBUG: Reset condition met, executing reset...");
    // Reset para configurações padrão
    $default_config = getDefaultDNSConfig();
    saveDNSConfig($config_file, $default_config);

    // Adiciona log do reset
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Reset de fábrica executado',
        'user' => $_SESSION['username'] ?? 'admin',
        'details' => 'Todas as configurações restauradas para padrão'
    ]);

    // Recarrega configurações após reset
    $dns_config = loadDNSConfig($config_file);

    $success = "✅ Configurações resetadas para padrão de fábrica!<br>";
    $success .= "DNS Primário: " . htmlspecialchars($default_config['primary_dns']) . "<br>";
    $success .= "DNS Secundário: " . htmlspecialchars($default_config['secondary_dns']);

    error_log("Factory reset executed - DNS restored to defaults");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WifiRouter - Administração</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #333;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .content {
            padding: 30px;
        }

        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .section h2 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .status-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }

        .status-item h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .status-item p {
            margin: 0;
            color: #666;
        }

        .warning-box {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeaa7;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📶 WifiRouter Pro - Painel Administrativo</h1>
            <div>
                <?php if ($is_logged_in): ?>
                    <span style="color: #ccc; margin-right: 10px;">Logado como: <?= $_SESSION['username'] ?></span>
                    <a href="index.php?logout=1" class="logout-btn">Sair</a>
                <?php else: ?>
                    <span style="color: #ffcc00; margin-right: 10px;">⚠️ Acesso não autenticado</span>
                    <a href="index.php" class="logout-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <div class="warning-box">
                <strong>⚠️ Aviso de Segurança:</strong> Esta é uma demonstração de dispositivo com vulnerabilidades intencionais para fins educacionais.
            </div>

            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Status do Sistema -->
            <div class="section">
                <h2>📊 Status do Sistema</h2>
                <div class="status-grid">
                    <div class="status-item">
                        <h4>Conexão WAN</h4>
                        <p style="color: green;">✅ Conectado</p>
                    </div>
                    <div class="status-item">
                        <h4>WiFi 2.4GHz</h4>
                        <p style="color: green;">✅ Ativo (12 clientes)</p>
                    </div>
                    <div class="status-item">
                        <h4>WiFi 5GHz</h4>
                        <p style="color: green;">✅ Ativo (8 clientes)</p>
                    </div>
                    <div class="status-item">
                        <a href="status.php" class="logout-btn" style="background: #28a745; margin-left: 10px;">📊 Página de Status</a>
                    </div>
                </div>
            </div>

            <!-- Configurações de DNS -->
            <div class="section">
                <h2>🌐 Configurações de DNS</h2>

                <!-- Status Atual das Configurações -->
                <div class="alert alert-info mb-3">
                    <h6><strong>📋 Configuração Atual:</strong></h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>DNS Primário:</strong><br>
                            <code><?= htmlspecialchars($dns_config['primary_dns']) ?></code>
                        </div>
                        <div class="col-md-3">
                            <strong>DNS Secundário:</strong><br>
                            <code><?= htmlspecialchars($dns_config['secondary_dns']) ?></code>
                        </div>
                        <div class="col-md-3">
                            <strong>Última Alteração:</strong><br>
                            <small><?= htmlspecialchars($dns_config['last_changed']) ?></small>
                        </div>
                        <div class="col-md-3">
                            <strong>Alterado por:</strong><br>
                            <small><?= htmlspecialchars($dns_config['changed_by']) ?></small>
                        </div>
                    </div>
                </div>

                <form method="POST" id="dnsForm">
                    <div class="form-group">
                        <label for="primary_dns">DNS Primário:</label>
                        <input type="text" id="primary_dns" name="primary_dns" value="<?= htmlspecialchars($dns_config['primary_dns']) ?>" placeholder="Ex: 8.8.8.8">
                    </div>
                    <div class="form-group">
                        <label for="secondary_dns">DNS Secundário (opcional):</label>
                        <input type="text" id="secondary_dns" name="secondary_dns" value="<?= htmlspecialchars($dns_config['secondary_dns']) ?>" placeholder="Ex: 8.8.4.4">
                    </div>
                    <button type="submit" class="btn">Salvar DNS</button>
                </form>
            </div>

            <!-- Ações Rápidas -->
            <div class="section">
                <h2>⚡ Ações Rápidas</h2>
                <button class="btn" onclick="rebootRouter()">Reiniciar Roteador</button>
                <button class="btn" onclick="updateFirmware()">Atualizar Firmware</button>

            </div>

            <!-- Log de Atividades -->
            <div class="section">
                <h2>📜 Log de Atividades</h2>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Ação</th>
                                <th>Usuário</th>
                                <th>Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mostra as últimas 10 atividades em ordem reversa
                            $activities = array_reverse(array_slice($activity_log, -10));
                            foreach ($activities as $activity):
                            ?>
                                <tr>
                                    <td><small><?= htmlspecialchars($activity['timestamp']) ?></small></td>
                                    <td>
                                        <?php if (strpos($activity['user'], 'Remoto') !== false): ?>
                                            <span class="badge badge-danger"><?= htmlspecialchars($activity['action']) ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-info"><?= htmlspecialchars($activity['action']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (strpos($activity['user'], 'Remoto') !== false): ?>
                                            <span class="text-danger"><?= htmlspecialchars($activity['user']) ?></span>
                                        <?php else: ?>
                                            <?= htmlspecialchars($activity['user']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= htmlspecialchars($activity['details']) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reset de Fábrica -->
            <div class="section">
                <h2>🔄 Reset de Configurações</h2>
                <p>Restaura todas as configurações para os valores padrão de fábrica.</p>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="reset_config" value="1">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza? Esta ação não pode ser desfeita!')">Executar Reset de Fábrica</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        console.log('🔧 WifiRouter Pro - Painel Administrativo Carregado');
        console.log('👤 Usuário logado:', '<?= $_SESSION['username'] ?>');
        console.log('⏰ Login em:', new Date().toISOString());

        // Monitora mudanças no formulário DNS
        document.getElementById('dnsForm').addEventListener('submit', function(e) {
            const primaryDns = document.getElementById('primary_dns').value;
            const secondaryDns = document.getElementById('secondary_dns').value;

            console.log('🌐 Alteração de DNS solicitada:');
            console.log('  - DNS Primário:', primaryDns);
            console.log('  - DNS Secundário:', secondaryDns);
            console.log('  - Timestamp:', new Date().toISOString());
        });

        function rebootRouter() {
            console.log('🔄 Reinicialização do roteador solicitada');
            // Simula execução via GET (vulnerabilidade demonstrativa)
            fetch('?cmd=reboot')
                .then(() => {
                    alert('Roteador será reiniciado em 10 segundos...');
                    console.log('✅ Comando de reinicialização enviado');
                });
        }

        function updateFirmware() {
            console.log('📦 Atualização de firmware solicitada');
            fetch('?cmd=update_firmware')
                .then(() => {
                    alert('Verificando atualizações de firmware...');
                    console.log('✅ Verificação de firmware iniciada');
                });
        }

        function resetToFactory() {
            if (confirm('ATENÇÃO: Esta ação irá apagar todas as configurações personalizadas. Continuar?')) {
                console.log('🏭 Reset de fábrica solicitado');
                i fetch('?reset_api.php?action=reset')
                    .then(() => {
                        alert('Reset de fábrica iniciado. O dispositivo será reiniciado.');
                        console.log('✅ Reset de fábrica executado');
                    });
            }
        }

        // Simula logs de atividade
        setInterval(() => {
            console.log(`📈 Status: ${Math.floor(Math.random() * 20) + 10} dispositivos conectados`);
        }, 30000);
    </script>
</body>

</html>