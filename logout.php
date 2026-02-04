<?php
require_once 'api/auth.php';

logout();
header('Location: login.php');
exit();
?>