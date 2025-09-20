<?php
session_start();

// ✅ SEM headers CORS permissivos (mais realista)
// Dispositivos IoT reais geralmente não têm CORS configurado

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
function loadDNSConfig($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: getDefaultDNSConfig();
    }
    return getDefaultDNSConfig();
}

function saveDNSConfig($file, $config) {
    file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT));
}

function addActivityLog($file, $activity) {
    $log = [];
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $log = json_decode($content, true) ?: [];
    }
    $log[] = $activity;
    if (count($log) > 50) {
        $log = array_slice($log, -50);
    }
    file_put_contents($file, json_encode($log, JSON_PRETTY_PRINT));
}

function getDefaultDNSConfig() {
    return [
        'primary_dns' => '8.8.8.8',
        'secondary_dns' => '8.8.4.4',
        'last_changed' => 'Configuração de fábrica',
        'changed_by' => 'Sistema'
    ];
}

// Carrega configurações dos arquivos
$dns_config = loadDNSConfig($config_file);

// DEBUG: Log what we loaded
error_log("REALISTIC: Config file: " . $config_file);
error_log("REALISTIC: DNS config loaded: " . json_encode($dns_config));

// DEBUG: Log incoming POST data
error_log("REALISTIC: POST data received: " . json_encode($_POST));
error_log("REALISTIC: Session data: " . json_encode($_SESSION));
error_log("REALISTIC: HTTP Origin: " . ($_SERVER['HTTP_ORIGIN'] ?? 'NOT SET'));
error_log("REALISTIC: HTTP Referer: " . ($_SERVER['HTTP_REFERER'] ?? 'NOT SET'));

// Processa alteração de DNS
if ($_POST['primary_dns'] ?? false) {
    $primary_dns = $_POST['primary_dns'];
    $secondary_dns = $_POST['secondary_dns'] ?? '';

    error_log("REALISTIC: Processing DNS change: Primary=$primary_dns, Secondary=$secondary_dns");

    // Salva as configurações no arquivo
    $changed_by = 'Ataque CSRF (origem suspeita)';
    if ($is_logged_in) {
        $changed_by = $_SESSION['username'] ?? 'admin';
    }

    // Detecta possível ataque CSRF analisando Origin/Referer
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($origin && $origin !== 'http://localhost:8181') {
        $changed_by = "CSRF de origem suspeita: $origin";
        error_log("REALISTIC: CSRF attack detected from origin: $origin");
    } elseif ($referer && !str_contains($referer, 'localhost:8181')) {
        $changed_by = "CSRF de referer suspeito: $referer";
        error_log("REALISTIC: CSRF attack detected from referer: $referer");
    }

    $dns_config = [
        'primary_dns' => $primary_dns,
        'secondary_dns' => $secondary_dns,
        'last_changed' => date('Y-m-d H:i:s'),
        'changed_by' => $changed_by
    ];
    saveDNSConfig($config_file, $dns_config);

    // DEBUG: Confirm save
    error_log("REALISTIC: Saved DNS config: " . json_encode($dns_config));

    // Recarrega as configurações para garantir que estão atualizadas
    $dns_config = loadDNSConfig($config_file);
    error_log("REALISTIC: Reloaded DNS config after save: " . json_encode($dns_config));

    // Adiciona ao log de atividades
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Configuração DNS alterada',
        'user' => $changed_by,
        'details' => "DNS: {$primary_dns} / {$secondary_dns}",
        'origin' => $origin ?: 'N/A',
        'referer' => $referer ?: 'N/A',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'N/A'
    ]);

    // Simula salvamento das configurações
    $success = "Configurações de DNS atualizadas com sucesso!<br>";
    $success .= "DNS Primário: " . htmlspecialchars($primary_dns) . "<br>";
    if ($secondary_dns) {
        $success .= "DNS Secundário: " . htmlspecialchars($secondary_dns);
    }

    // Log para demonstração
    error_log("REALISTIC: DNS Change: Primary={$primary_dns}, Secondary={$secondary_dns}");
}

// Processa comando personalizado via GET (vulnerabilidade intencional)
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    $success = "Comando executado: " . htmlspecialchars($cmd);

    // Detecta possível ataque CSRF
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $user = 'Comando via GET';

    if ($referer && !str_contains($referer, 'localhost:8181')) {
        $user = "CSRF via GET de: $referer";
        error_log("REALISTIC: CSRF GET attack detected from: $referer");
    }

    // Adiciona ao log de atividades
    addActivityLog($log_file, [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => 'Comando executado via GET',
        'user' => $user,
        'details' => "Comando: {$cmd}",
        'origin' => $origin ?: 'N/A',
        'referer' => $referer ?: 'N/A'
    ]);

    error_log("REALISTIC: Command executed via GET: {$cmd}");
}

// Processa reset de configurações
if ($_POST['reset_config'] ?? false) {
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

    error_log("REALISTIC: Factory reset executed - DNS restored to defaults");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WifiRouter - Administração Realista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .config-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📶 WifiRouter Pro - Configuração Realista</h1>
            <p>Simulação sem headers CORS permissivos</p>
        </div>

        <div class="content">
            <div class="alert alert-danger">
                <strong>⚠️ Demonstração Realista:</strong> Este roteador NÃO tem headers CORS permissivos,
                simulando um dispositivo IoT real. Ataques CSRF ainda são possíveis através de formulários
                e imagens, sem necessidade de ler respostas.
            </div>

            <?php if ($success): ?>
            <div class="alert alert-warning"><?= $success ?></div>
            <?php endif; ?>

            <div class="config-display">
                <h3>🌐 Configuração DNS Atual</h3>
                <p><strong>DNS Primário:</strong> <code><?= htmlspecialchars($dns_config['primary_dns']) ?></code></p>
                <p><strong>DNS Secundário:</strong> <code><?= htmlspecialchars($dns_config['secondary_dns']) ?></code></p>
                <p><strong>Última Alteração:</strong> <?= htmlspecialchars($dns_config['last_changed']) ?></p>
                <p><strong>Alterado por:</strong> <em><?= htmlspecialchars($dns_config['changed_by']) ?></em></p>
            </div>

            <form method="POST">
                <h3>Alterar Configurações DNS</h3>
                <div class="form-group">
                    <label for="primary_dns">DNS Primário:</label>
                    <input type="text" id="primary_dns" name="primary_dns" value="<?= htmlspecialchars($dns_config['primary_dns']) ?>">
                </div>
                <div class="form-group">
                    <label for="secondary_dns">DNS Secundário:</label>
                    <input type="text" id="secondary_dns" name="secondary_dns" value="<?= htmlspecialchars($dns_config['secondary_dns']) ?>">
                </div>
                <button type="submit" class="btn">Salvar Configurações</button>
            </form>

            <div style="margin-top: 30px;">
                <h3>🔄 Reset de Configurações</h3>
                <p>Restaura todas as configurações para os valores padrão de fábrica.</p>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="reset_config" value="1">
                    <button type="submit" class="btn" style="background: #e74c3c; color: white;" onclick="return confirm('Tem certeza? Esta ação não pode ser desfeita!')">
                        🏭 Executar Reset de Fábrica
                    </button>
                </form>
            </div>

            <div class="alert alert-warning" style="margin-top: 30px;">
                <strong>🔍 Logs de Detecção:</strong> Este roteador registra Origin e Referer de todas as requisições
                para detectar possíveis ataques CSRF. Verifique os logs para evidências de comprometimento.
            </div>
        </div>
    </div>

    <script>
        console.log('📶 Roteador Realista (sem CORS permissivo) carregado');
        console.log('🔒 Headers CORS restritivos ativados');
        console.log('🕵️ Monitoramento de Origin/Referer ativo');
    </script>
</body>
</html>