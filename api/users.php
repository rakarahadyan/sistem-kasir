<?php
require_once __DIR__ . '/../config/database.php';

function getUsers($search = '') {
    $db = Database::getInstance()->getConnection();
    
    $sql = "SELECT id, name, username, role, created_at FROM users WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($search)) {
        $sql .= " AND (name LIKE ? OR username LIKE ?)";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ss";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    if (!empty($params)) {
        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query($sql);
    }
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    return $users;
}

function getUser($id) {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("SELECT id, name, username, role, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return ['success' => true, 'data' => $row];
    }
    
    return ['success' => false, 'message' => 'User tidak ditemukan'];
}

function createUser($name, $username, $password, $role) {
    $db = Database::getInstance()->getConnection();
    
    // Cek apakah username sudah ada
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Username sudah digunakan'];
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (name, username, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $username, $hashed_password, $role);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'User berhasil ditambahkan', 'id' => $db->insert_id];
    }
    
    return ['success' => false, 'message' => 'Gagal menambahkan user'];
}

function updateUser($id, $name, $username, $role) {
    $db = Database::getInstance()->getConnection();
    
    // Cek apakah username sudah digunakan user lain
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Username sudah digunakan'];
    }
    
    $stmt = $db->prepare("UPDATE users SET name = ?, username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $username, $role, $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'User berhasil diupdate'];
    }
    
    return ['success' => false, 'message' => 'Gagal mengupdate user'];
}

function updatePassword($id, $password) {
    $db = Database::getInstance()->getConnection();
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Password berhasil diubah'];
    }
    
    return ['success' => false, 'message' => 'Gagal mengubah password'];
}

function deleteUser($id) {
    $db = Database::getInstance()->getConnection();
    
    // Jangan hapus user sendiri
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
        return ['success' => false, 'message' => 'Tidak dapat menghapus user sendiri'];
    }
    
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'User berhasil dihapus'];
    }
    
    return ['success' => false, 'message' => 'Gagal menghapus user'];
}

// API Handler
if (basename($_SERVER['PHP_SELF']) == 'users.php') {
    session_start();
    header('Content-Type: application/json');
    
    // Cek login
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    // Cek role admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method == 'GET') {
        if (isset($_GET['id'])) {
            echo json_encode(getUser($_GET['id']));
        } else {
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $users = getUsers($search);
            echo json_encode(['success' => true, 'data' => $users]);
        }
    }
    else if ($method == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['action']) && $data['action'] == 'update_password') {
            echo json_encode(updatePassword($data['id'], $data['password']));
        }
        else if (isset($data['action']) && $data['action'] == 'update') {
            echo json_encode(updateUser($data['id'], $data['name'], $data['username'], $data['role']));
        }
        else if (isset($data['action']) && $data['action'] == 'delete') {
            echo json_encode(deleteUser($data['id']));
        }
        else {
            echo json_encode(createUser($data['name'], $data['username'], $data['password'], $data['role']));
        }
    }
    else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
    exit;
}
