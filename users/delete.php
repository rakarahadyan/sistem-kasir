<?php
require_once '../api/auth.php';
requireLogin();
checkRole('admin');
require_once '../api/users.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

$result = deleteUser($id);

if ($result['success']) {
    header('Location: index.php?msg=deleted');
} else {
    header('Location: index.php?error=' . urlencode($result['message']));
}
exit();
