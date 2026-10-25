<?php

require_once __DIR__.'/../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($uri === '/api/health') {
    echo json_encode(['status' => 'ok']);
    exit;
}

if ($uri === '/api/game/enemies' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'enemies' => [
            ['id' => 1, 'name' => 'Slime', 'hp' => 10],
            ['id' => 2, 'name' => 'Goblin', 'hp' => 15]
        ]
    ]);
    exit;
}

if ($uri === '/api/agent/chat' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['message'] ?? '';
    echo json_encode([
        'response' => "Helper received: '{$message}'. I can use tools like enemies_get to assist."
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found']);
