<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: user/dashboard.php");
    } else {
        echo "Role tidak dikenali.";
    }
    exit;
}

header("Location: login.php");
exit;