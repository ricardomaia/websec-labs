<?php
// API simples para verificar status do roteador
// Headers CORS mais permissivos
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

// Determina se foi comprometido
$is_compromised = ($dns_config['primary_dns'] !== '8.8.8.8');

// Pega logs recentes de ataques
$attack_logs = array_filter($activity_log, function($log) {
    return strpos($log['user'], 'CSRF') !== false || strpos($log['user'], 'Remoto') !== false;
});

$response = [
    'status' => $is_compromised ? 'COMPROMISED' : 'NORMAL',
    'dns_config' => $dns_config,
    'is_compromised' => $is_compromised,
    'attack_logs' => array_slice(array_reverse($attack_logs), 0, 5),
    'timestamp' => date('Y-m-d H:i:s'),
    'files_exist' => [
        'config' => file_exists($config_file),
        'log' => file_exists($log_file)
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>