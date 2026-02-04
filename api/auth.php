<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['user']);
}

function checkRole($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $requiredRole) {
        header('Location: /sistem-kasir/login.php');
        exit();
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /sistem-kasir/login.php');
        exit();
    }
}

function login($username, $password) {
    try {
        require_once __DIR__ . '/../config/database.php';
        
        $db = Database::getInstance()->getConnection();
        
    
        $username = $db->real_escape_string($username);
        
    
        $query = "SELECT id, username, name, password, role FROM users WHERE username = '$username'";
        $result = $db->query($query);
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $user = $result->fetch_assoc();
        
    
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        
    
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        return true;
    } catch (Exception $e) {
        error_log('Login error: ' . $e->getMessage());
        return false;
    }
}

function logout() {
    session_unset();
    session_destroy();
}

function currentUser() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'name' => $_SESSION['name'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

if (basename($_SERVER['PHP_SELF']) == 'auth.php') {
    header('Content-Type: application/json');
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method == 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['action'])) {
        
            if ($input['action'] == 'logout') {
                logout();
                echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
                exit;
            }
        } else {
        
            $username = $input['username'] ?? '';
            $password = $input['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Username dan password harus diisi']);
                exit;
            }
            
            if (login($username, $password)) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Login berhasil',
                    'user' => currentUser()
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
            }
        }
    } elseif ($method == 'GET') {
    
        if (isLoggedIn()) {
            echo json_encode([
                'success' => true,
                'logged_in' => true,
                'user' => currentUser()
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'logged_in' => false
            ]);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
    exit;
}
?>