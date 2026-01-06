<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check user role
function checkRole($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $requiredRole) {
        header('Location: /sistem-kasir/login.php');
        exit();
    }
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /sistem-kasir/login.php');
        exit();
    }
}

// Simulate database user data
$users = [
    'admin' => [
        'id' => 1,
        'name' => 'Administrator',
        'password' => '123456',
        'role' => 'admin'
    ],
    'kasir' => [
        'id' => 2,
        'name' => 'Kasir',
        'password' => '123456',
        'role' => 'kasir'
    ]
];

// Login function
function login($username, $password) {
    global $users;
    
    if (isset($users[$username]) && $users[$username]['password'] == $password) {
        $_SESSION['user_id'] = $users[$username]['id'];
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $users[$username]['name'];
        $_SESSION['role'] = $users[$username]['role'];
        return true;
    }
    return false;
}

// Get current user info
function currentUser() {
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
?>