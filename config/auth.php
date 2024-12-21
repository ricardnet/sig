<?php
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /sig/views/auth/login.php");
        exit();
    }
}

function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /sig/views/dashboard/index.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?> 