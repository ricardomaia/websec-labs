<?php
// Página simples para verificar configurações atuais
$config_file = '/var/www/html/data/dns_config.json';
$log_file = '/var/www/html/data/activity_log.json';

function loadDNSConfig($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $config = json_decode($content, true);
        return $config ?: getDefaultDNSConfig();
    }
    return getDefaultDNSConfig();
}

function getDefaultDNSConfig() {
    return [
        'primary_dns' => '8.8.8.8',
        'secondary_dns' => '8.8.4.4',
        'last_changed' => 'Configuração de fábrica',
        'changed_by' => 'Sistema'
    ];
}

function loadActivityLog($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

$dns_config = loadDNSConfig($config_file);
$activity_log = loadActivityLog($log_file);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Status do Roteador</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .config { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .log { background: #fff; border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
        .compromised { background: #ffe6e6; border-color: #ff9999; }
        .normal { background: #e6ffe6; border-color: #99ff99; }
        .malicious { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>📊 Status do Roteador Wi-Fi</h1>

    <div class="config <?= ($dns_config['primary_dns'] !== '8.8.8.8') ? 'compromised' : 'normal' ?>">
        <h2>🌐 Configuração DNS Atual</h2>
        <p><strong>DNS Primário:</strong>
            <code class="<?= ($dns_config['primary_dns'] !== '8.8.8.8') ? 'malicious' : '' ?>">
                <?= htmlspecialchars($dns_config['primary_dns']) ?>
            </code>
        </p>
        <p><strong>DNS Secundário:</strong>
            <code class="<?= ($dns_config['secondary_dns'] !== '8.8.4.4') ? 'malicious' : '' ?>">
                <?= htmlspecialchars($dns_config['secondary_dns']) ?>
            </code>
        </p>
        <p><strong>Última Alteração:</strong> <?= htmlspecialchars($dns_config['last_changed']) ?></p>
        <p><strong>Alterado por:</strong> <?= htmlspecialchars($dns_config['changed_by']) ?></p>
    </div>

    <div class="config">
        <h2>📁 Informações dos Arquivos</h2>
        <p><strong>Arquivo de Config:</strong> <?= $config_file ?></p>
        <p><strong>Existe:</strong> <?= file_exists($config_file) ? '✅ SIM' : '❌ NÃO' ?></p>
        <?php if (file_exists($config_file)): ?>
            <p><strong>Tamanho:</strong> <?= filesize($config_file) ?> bytes</p>
            <p><strong>Última Modificação:</strong> <?= date('Y-m-d H:i:s', filemtime($config_file)) ?></p>
        <?php endif; ?>

        <p><strong>Arquivo de Log:</strong> <?= $log_file ?></p>
        <p><strong>Existe:</strong> <?= file_exists($log_file) ? '✅ SIM' : '❌ NÃO' ?></p>
        <?php if (file_exists($log_file)): ?>
            <p><strong>Tamanho:</strong> <?= filesize($log_file) ?> bytes</p>
            <p><strong>Última Modificação:</strong> <?= date('Y-m-d H:i:s', filemtime($log_file)) ?></p>
        <?php endif; ?>
    </div>

    <div class="config">
        <h2>📜 Log de Atividades (Últimas 5)</h2>
        <?php if (empty($activity_log)): ?>
            <p>Nenhuma atividade registrada.</p>
        <?php else: ?>
            <?php $recent_logs = array_slice(array_reverse($activity_log), 0, 5); ?>
            <?php foreach ($recent_logs as $log): ?>
                <div class="log <?= (strpos($log['user'], 'Remoto') !== false) ? 'compromised' : 'normal' ?>">
                    <strong><?= htmlspecialchars($log['timestamp']) ?></strong> -
                    <strong><?= htmlspecialchars($log['action']) ?></strong><br>
                    <small>Usuário: <?= htmlspecialchars($log['user']) ?></small><br>
                    <small>Detalhes: <?= htmlspecialchars($log['details']) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="config">
        <h2>🔧 Ações</h2>
        <p><a href="index.php">🏠 Voltar ao Login</a></p>
        <p><a href="admin.php">⚙️ Painel Administrativo</a></p>
        <p><a href="test_attack.html">🧪 Teste de Ataque</a></p>
        <button onclick="location.reload()">🔄 Atualizar Status</button>
    </div>

    <script>
        // Auto-refresh a cada 5 segundos
        setTimeout(() => {
            location.reload();
        }, 5000);

        console.log('📊 Status do Roteador:');
        console.log('DNS Primário:', '<?= $dns_config['primary_dns'] ?>');
        console.log('DNS Secundário:', '<?= $dns_config['secondary_dns'] ?>');
        console.log('Arquivo Config Existe:', <?= file_exists($config_file) ? 'true' : 'false' ?>);
        console.log('Arquivo Log Existe:', <?= file_exists($log_file) ? 'true' : 'false' ?>);
    </script>
</body>
</html>