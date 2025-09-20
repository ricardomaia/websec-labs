<?php
// API para reset do roteador
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$config_file = '/var/www/html/data/dns_config.json';
$log_file = '/var/www/html/data/activity_log.json';

function getDefaultDNSConfig() {
    return [
        'primary_dns' => '8.8.8.8',
        'secondary_dns' => '8.8.4.4',
        'last_changed' => 'Configuração de fábrica',
        'changed_by' => 'Sistema'
    ];
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

// Processa reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action']) && $_GET['action'] === 'reset') {
    try {
        // Reset configurações para padrão
        $default_config = getDefaultDNSConfig();
        saveDNSConfig($config_file, $default_config);

        // Adiciona log do reset
        addActivityLog($log_file, [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => 'Reset de fábrica via API',
            'user' => 'Sistema',
            'details' => 'Configurações restauradas via API'
        ]);

        $response = [
            'success' => true,
            'message' => 'Reset executado com sucesso',
            'dns_config' => $default_config,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        error_log("API: Factory reset executed successfully");

    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Erro ao executar reset: ' . $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        error_log("API: Factory reset failed: " . $e->getMessage());
    }

} else {
    // Retorna status atual
    $dns_config = getDefaultDNSConfig();
    if (file_exists($config_file)) {
        $content = file_get_contents($config_file);
        $dns_config = json_decode($content, true) ?: $dns_config;
    }

    $response = [
        'success' => true,
        'message' => 'Status atual',
        'dns_config' => $dns_config,
        'reset_available' => true,
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>