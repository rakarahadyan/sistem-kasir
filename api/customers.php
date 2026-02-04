<?php
require_once __DIR__ . '/../config/database.php';

// Function untuk get semua customers (bisa dipanggil langsung dari file PHP)
function getCustomers($search = '') {
    $db = Database::getInstance()->getConnection();
    if ($search) {
        $searchParam = '%' . $search . '%';
        $stmt = $db->prepare('SELECT id, name, phone, created_at FROM customers WHERE name LIKE ? OR phone LIKE ? ORDER BY name');
        $stmt->bind_param('ss', $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query('SELECT id, name, phone, created_at FROM customers ORDER BY name');
    }
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Function untuk get 1 customer by ID (untuk edit)
function getCustomer($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT * FROM customers WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function untuk create customer
function createCustomer($name, $phone) {
    $db = Database::getInstance()->getConnection();
    $name = trim($name);
    $phone = trim($phone);
    
    if (empty($name) || empty($phone)) {
        return ['success' => false, 'message' => 'Nama dan telepon harus diisi'];
    }
    
    $stmt = $db->prepare('INSERT INTO customers (name, phone) VALUES (?, ?)');
    $stmt->bind_param('ss', $name, $phone);
    
    if ($stmt->execute()) {
        return ['success' => true, 'id' => $db->insert_id];
    }
    return ['success' => false, 'message' => 'Gagal menambahkan pelanggan'];
}

// Function untuk update customer
function updateCustomer($id, $name, $phone) {
    $db = Database::getInstance()->getConnection();
    $name = trim($name);
    $phone = trim($phone);
    
    if (empty($name) || empty($phone)) {
        return ['success' => false, 'message' => 'Nama dan telepon harus diisi'];
    }
    
    $stmt = $db->prepare('UPDATE customers SET name = ?, phone = ? WHERE id = ?');
    $stmt->bind_param('ssi', $name, $phone, $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal mengupdate pelanggan'];
}

// Function untuk delete customer
function deleteCustomer($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('DELETE FROM customers WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Gagal menghapus pelanggan'];
}

// Handler untuk API request (ketika file ini di-request sebagai endpoint)
if (basename($_SERVER['PHP_SELF']) == 'customers.php') {
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
            $customer = getCustomer($id);
            if (!$customer) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pelanggan tidak ditemukan']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $customer]);
            exit;
        }
        
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $customers = getCustomers($search);
        echo json_encode(['success' => true, 'data' => $customers]);
        exit;
    }
    
    if ($method === 'POST') {
        // Cek apakah ini request delete
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            $id = (int)$_POST['id'];
            $result = deleteCustomer($id);
            echo json_encode($result);
            exit;
        }
        
        // Cek apakah ini request update
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $result = updateCustomer($id, $name, $phone);
            echo json_encode($result);
            exit;
        }
        
        // Request create
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $result = createCustomer($name, $phone);
        http_response_code($result['success'] ? 201 : 400);
        echo json_encode($result);
        exit;
    }
    
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
?>