<?php
if ($_SESSION['role'] !== 'admin') {
    die('Akses ditolak. Halaman ini hanya untuk admin.');
}
?>