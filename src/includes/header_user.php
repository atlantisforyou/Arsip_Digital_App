<?php
require_once '../config/koneksi.php';

// Ambil pengaturan aplikasi
$setting = [];
$res = $conn->query("SELECT name, value FROM settings");
while ($s = $res->fetch_assoc()) {
    $setting[$s['name']] = $s['value'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $setting['nama_aplikasi'] ?? 'User Panel' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --twilight-bronze: #8B6B4D;
            --twilight-light: #D4B78F;
            --twilight-dark: #5A4A3A;
            --twilight-accent: #C19A6B;
            --twilight-bg: #F5EFE6;
        }

        body {
            background-color: var(--twilight-bg);
            margin: 0;
            padding: 0;
        }

        .navbar-custom {
            background: linear-gradient(90deg, var(--twilight-dark), var(--twilight-bronze));
            padding: 0.8rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: white;
            flex-wrap: wrap;
        }

        .navbar-custom .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            color: var(--twilight-light);
            text-decoration: none;
        }

        .navbar-custom .brand-logo img {
            height: 36px;
            width: 36px;
            object-fit: contain;
            border-radius: 5px;
            background-color: #fff;
            padding: 2px;
            border: 1px solid #ccc;
        }

        .navbar-custom .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .navbar-custom .nav-links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .navbar-custom .nav-links a i {
            margin-right: 6px;
        }

        .navbar-custom .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateY(-2px);
        }

        .navbar-custom .nav-links .active {
            background-color: var(--twilight-accent);
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="navbar-custom">
    <a class="brand-logo" href="#">
        <img src="../public/uploads/logo/<?= $setting['logo'] ?? 'default.png' ?>" alt="Logo">
        <?= $setting['nama_aplikasi'] ?? 'Aplikasi Arsip Digital' ?>
    </a>

    <div class="nav-links">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="event.php" class="<?= basename($_SERVER['PHP_SELF']) === 'event.php' ? 'active' : '' ?>">
            <i class="fas fa-calendar-alt"></i> Kegiatan
        </a>
        <a href="dokumen.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dokumen.php' ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i> Dokumen
        </a>
        <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) === 'laporan.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i> Laporan
        </a>
        <a href="komentar.php" class="<?= basename($_SERVER['PHP_SELF']) === 'komentar.php' ? 'active' : '' ?>">
            <i class="fas fa-comments"></i> Komentar
        </a>
        <a href="notifikasi.php" class="<?= basename($_SERVER['PHP_SELF']) === 'notifikasi.php' ? 'active' : '' ?>">
            <i class="fas fa-bell"></i> Notifikasi
        </a>
        <a href="../auth/logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="container-fluid mt-4">