<?php
header('Content-Type: application/json; charset=utf-8');

// Simple fake in-memory dataset for categories (id, name)
// In production, replace with DB queries.
$static = [
    ['id' => 1, 'name' => 'Minuman'],
    ['id' => 2, 'name' => 'Makanan'],
    ['id' => 3, 'name' => 'Rokok'],
    ['id' => 4, 'name' => 'Perlengkapan']
];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(['success' => true, 'data' => $static]);
    exit;
}

// Parse incoming JSON body for POST/PUT
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($method === 'POST') {
    $name = trim($input['name'] ?? '');
    if ($name === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Name is required']);
        exit;
    }
    // fake created id
    $newId = max(array_column($static, 'id')) + 1;
    $created = ['id' => $newId, 'name' => $name];
    echo json_encode(['success' => true, 'data' => $created]);
    exit;
}

if ($method === 'PUT') {
    // mimic update
    $id = intval($_GET['id'] ?? 0);
    $name = trim($input['name'] ?? '');
    if ($id <= 0 || $name === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid id or name']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => ['id' => $id, 'name' => $name]]);
    exit;
}

if ($method === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid id']);
        exit;
    }
    echo json_encode(['success' => true, 'message' => 'Deleted', 'id' => $id]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);

?>
