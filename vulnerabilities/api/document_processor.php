<?php

require_once __DIR__ . '/src/DocumentManager.php';
require_once __DIR__ . '/src/FileHelper.php';
require_once __DIR__ . '/src/PathProcessor.php';
require_once __DIR__ . '/src/PathValidator.php';
require_once __DIR__ . '/src/SecurityFilter.php';
require_once __DIR__ . '/src/DocumentReader.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['document'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing document parameter']);
        exit;
    }
    
    $manager = new DocumentManager();
    $result = $manager->processRequest($input);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Document processed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Document not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
