<?php
require_once __DIR__ . '/../config/database.php';

// Function untuk get semua categories (bisa dipanggil langsung dari file PHP)
function getCategories($search = '') {
    $db = Database::getInstance()->getConnection();
    if ($search) {
        $searchParam = '%' . $search . '%';
        $stmt = $db->prepare('SELECT id, name, created_at FROM categories WHERE name LIKE ? ORDER BY name');
        $stmt->bind_param('s', $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query('SELECT id, name, created_at FROM categories ORDER BY name');
    }
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    return $data;
}

// Function untuk get 1 category by ID (untuk edit)
function getCategory($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT id, name, created_at FROM categories WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function untuk create category
function createCategory($name) {
    $db = Database::getInstance()->getConnection();
    $name = trim($name);
    if (empty($name)) {
        return ['success' => false, 'message' => 'Nama kategori harus diisi'];
    }
    
    $stmt = $db->prepare('INSERT INTO categories (name) VALUES (?)');
    $stmt->bind_param('s', $name);
    
    if ($stmt->execute()) {
        return ['success' => true, 'id' => $db->insert_id];
    }
    return ['success' => false, 'message' => 'Gagal menambahkan kategori'];
}

// Function untuk update category
function updateCategory($id, $name) {
    $db = Database::getInstance()->getConnection();
    $name = trim($name);
    if (empty($name)) {
        return ['success' => false, 'message' => 'Nama kategori harus diisi'];
    }
    
    $stmt = $db->prepare('UPDATE categories SET name = ? WHERE id = ?');
    $stmt->bind_param('si', $name, $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal mengupdate kategori'];
}

// Function untuk delete category
function deleteCategory($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal menghapus kategori'];
}

// Handler untuk API request (ketika file ini di-request sebagai endpoint)
if (basename($_SERVER['PHP_SELF']) == 'categories.php') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/auth.php';
    
    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $category = getCategory($id);
            if (!$category) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Kategori tidak ditemukan']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $category]);
            exit;
        }
        
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $categories = getCategories($search);
        echo json_encode(['success' => true, 'data' => $categories]);
        exit;
    }
    
    if ($method === 'POST') {
        // Cek apakah ini request delete
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            $id = (int)$_POST['id'];
            $result = deleteCategory($id);
            echo json_encode($result);
            exit;
        }
        
        // Cek apakah ini request update
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $name = $_POST['name'] ?? '';
            $result = updateCategory($id, $name);
            echo json_encode($result);
            exit;
        }
        
        // Request create
        $name = $_POST['name'] ?? '';
        $result = createCategory($name);
        http_response_code($result['success'] ? 201 : 400);
        echo json_encode($result);
        exit;
    }
    
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
?>
