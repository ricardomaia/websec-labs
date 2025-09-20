<?php
// Headers CORS para demonstração educacional
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Credenciais hardcoded (simulando dispositivo com credenciais padrão)
$valid_username = 'admin';
$valid_password = 'admin';

$error = '';
$success = '';

// Processa logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Processa login
if ($_POST['username'] ?? false) {
    if ($_POST['username'] === $valid_username && $_POST['password'] === $valid_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $valid_username;

        // Adiciona log de login bem-sucedido
        $log_file = '/var/www/html/data/activity_log.json';
        $data_dir = dirname($log_file);
        if (!is_dir($data_dir)) {
            mkdir($data_dir, 0777, true);
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

        addActivityLog($log_file, [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => 'Login realizado',
            'user' => $valid_username,
            'details' => 'Login bem-sucedido no painel administrativo'
        ]);

        header('Location: admin.php');
        exit;
    } else {
        // Log de tentativa de login inválida
        $log_file = '/var/www/html/data/activity_log.json';
        $data_dir = dirname($log_file);
        if (!is_dir($data_dir)) {
            mkdir($data_dir, 0777, true);
        }

        if (!function_exists('addActivityLog')) {
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
        }

        addActivityLog($log_file, [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => 'Tentativa de login inválida',
            'user' => 'Desconhecido (' . ($_POST['username'] ?? '') . ')',
            'details' => 'Credenciais incorretas fornecidas'
        ]);

        $error = 'Credenciais inválidas!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WifiRouter - Painel de Administração</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        .logo p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .default-creds {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #ffeaa7;
            font-size: 12px;
        }
        .device-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .device-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .device-info p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>📶 WifiRouter Pro</h1>
            <p>Painel de Administração v2.4.1</p>
        </div>

        <div class="device-info">
            <h3>Informações do Dispositivo</h3>
            <p><strong>Modelo:</strong> WifiRouter Pro X500</p>
            <p><strong>IP LAN:</strong> 192.168.1.1</p>
            <p><strong>Firmware:</strong> v2.4.1 (2020-03-15)</p>
            <p><strong>Status:</strong> <span style="color: green;">●</span> Online</p>
        </div>

        <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required value="admin">
            </div>

            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="default-creds">
            <strong>💡 Dica:</strong> Este é um dispositivo de demonstração com credenciais padrão.<br>
            Usuário: <code>admin</code> | Senha: <code>admin</code>
        </div>
    </div>

    <script>
        // Log de tentativas de login para demonstração
        console.log('🔐 WifiRouter Pro - Painel de Login');
        console.log('📝 Credenciais padrão: admin/admin');

        // Simula logs do dispositivo
        const loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', function(e) {
            console.log('🔍 Tentativa de login detectada');
            console.log('👤 Usuário:', document.getElementById('username').value);
            console.log('⏰ Timestamp:', new Date().toISOString());
        });
    </script>
</body>
</html>