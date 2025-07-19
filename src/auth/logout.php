<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require_once '../config/koneksi.php';
    $user_id = $_SESSION['user_id'];
    $conn->query("INSERT INTO activity_logs (user_id, activity) VALUES ($user_id, 'Logout')");
}

session_unset();
session_destroy();

header("Location: ../login.php");
exit;