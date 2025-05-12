<?php
include 'db.php';
session_start();

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

if (!isAuthenticated() && basename($_SERVER['PHP_SELF']) == 'index.php') {
    header('Location: login.php');
    exit();
}elseif (isAuthenticated() && (basename($_SERVER['PHP_SELF']) == 'register.php' || basename($_SERVER['PHP_SELF']) == 'login.php')) {
    header('Location: index.php');
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

?>