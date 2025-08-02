<?php

require_once __DIR__ . '/src/NetworkService.php';
require_once __DIR__ . '/src/HostValidator.php';
require_once __DIR__ . '/src/DataProcessor.php';
require_once __DIR__ . '/src/SecurityCleaner.php';
require_once __DIR__ . '/src/CommandExecutor.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['host'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing host parameter']);
        exit;
    }
    
    $service = new NetworkService();
    $result = $service->handleRequest($input);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'output' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ping failed']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
