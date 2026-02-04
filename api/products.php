<?php
require_once __DIR__ . '/../config/database.php';

// Function untuk get semua products
function getProducts($search = '') {
    $db = Database::getInstance()->getConnection();
    if ($search) {
        $searchParam = '%' . $search . '%';
        $stmt = $db->prepare('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.name LIKE ? OR p.barcode LIKE ? ORDER BY p.name');
        $stmt->bind_param('ss', $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.name');
    }
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Function untuk get 1 product by ID
function getProduct($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function untuk get barcode by product name
function getBarcodeByName($name) {
    $db = Database::getInstance()->getConnection();
    $name = $db->real_escape_string($name);
    
    // Cari produk yang namanya mengandung keyword
    $query = "SELECT id, barcode, name, price, stock FROM products WHERE name LIKE '%$name%' AND is_active = 1 LIMIT 1";
    $result = $db->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Function untuk create product
function createProduct($barcode, $name, $category_id, $price, $cost, $stock, $is_active = 1) {
    $db = Database::getInstance()->getConnection();
    $barcode = trim($barcode);
    $name = trim($name);
    
    if (empty($barcode) || empty($name) || empty($category_id)) {
        return ['success' => false, 'message' => 'Barcode, nama produk, dan kategori harus diisi'];
    }
    
    $stmt = $db->prepare('INSERT INTO products (barcode, name, category_id, price, cost, stock, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssiddii', $barcode, $name, $category_id, $price, $cost, $stock, $is_active);
    
    if ($stmt->execute()) {
        return ['success' => true, 'id' => $db->insert_id];
    }
    return ['success' => false, 'message' => 'Gagal menambahkan produk'];
}

// Function untuk update product
function updateProduct($id, $barcode, $name, $category_id, $price, $cost, $stock, $is_active = 1) {
    $db = Database::getInstance()->getConnection();
    $barcode = trim($barcode);
    $name = trim($name);
    
    if (empty($barcode) || empty($name) || empty($category_id)) {
        return ['success' => false, 'message' => 'Barcode, nama produk, dan kategori harus diisi'];
    }
    
    $stmt = $db->prepare('UPDATE products SET barcode = ?, name = ?, category_id = ?, price = ?, cost = ?, stock = ?, is_active = ? WHERE id = ?');
    $stmt->bind_param('ssiiddii', $barcode, $name, $category_id, $price, $cost, $stock, $is_active, $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal mengupdate produk'];
}

// Function untuk delete product
function deleteProduct($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal menghapus produk'];
}

// Handler untuk API request
if (basename($_SERVER['PHP_SELF']) == 'products.php') {
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
        // Jika request barcode by name
        if (isset($_GET['action']) && $_GET['action'] == 'get_barcode' && isset($_GET['name'])) {
            $name = $_GET['name'];
            $product = getBarcodeByName($name);
            if ($product) {
                echo json_encode(['success' => true, 'data' => $product]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
            }
            exit;
        }
        
        // Jika request by ID
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $product = getProduct($id);
            if (!$product) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $product]);
            exit;
        }
        
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $products = getProducts($search);
        echo json_encode(['success' => true, 'data' => $products]);
        exit;
    }
    
    if ($method === 'POST') {
        // Cek apakah ini request delete
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            $id = (int)$_POST['id'];
            $result = deleteProduct($id);
            echo json_encode($result);
            exit;
        }
        
        // Cek apakah ini request update
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $barcode = $_POST['barcode'] ?? '';
            $name = $_POST['name'] ?? '';
            $category_id = (int)($_POST['category_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $cost = (float)($_POST['cost'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            $is_active = (int)($_POST['is_active'] ?? 1);
            $result = updateProduct($id, $barcode, $name, $category_id, $price, $cost, $stock, $is_active);
            echo json_encode($result);
            exit;
        }
        
        // Request create
        $barcode = $_POST['barcode'] ?? '';
        $name = $_POST['name'] ?? '';
        $category_id = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $cost = (float)($_POST['cost'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $is_active = (int)($_POST['is_active'] ?? 1);
        $result = createProduct($barcode, $name, $category_id, $price, $cost, $stock, $is_active);
        http_response_code($result['success'] ? 201 : 400);
        echo json_encode($result);
        exit;
    }
    
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
?>