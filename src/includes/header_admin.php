<?php
require_once '../config/koneksi.php';

// Ambil setting aplikasi dari DB
$setting = [];
$res = $conn->query("SELECT name, value FROM settings");
while ($s = $res->fetch_assoc()) {
    $setting[$s['name']] = $s['value'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $setting['nama_aplikasi'] ?? 'Admin Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --twilight-dark: #39444E;
            --twilight-bronze: #8B6B4D;
            --twilight-accent: #C19A6B;
            --twilight-light: #D4B78F;
            --twilight-bg: #F5EFE6;
        }

        body {
            background-color: var(--twilight-bg);
        }

        .navbar-custom {
            background: linear-gradient(90deg, var(--twilight-dark), #2E363F);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            flex-wrap: wrap;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--twilight-light) !important;
            text-decoration: none;
        }

        .app-logo {
            height: 36px;
            width: 36px;
            object-fit: contain;
            border-radius: 5px;
            background-color: #fff;
            padding: 2px;
            border: 1px solid #ccc;
        }

        .navbar-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .navbar-menu a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .navbar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .navbar-menu .active {
            background-color: var(--twilight-bronze);
            color: white;
            font-weight: 500;
            border-bottom: 2px solid var(--twilight-accent);
        }

        .navbar-menu i {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .navbar-custom {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-menu {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .navbar-brand {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="../public/uploads/logo/<?= $setting['logo'] ?? 'default.png' ?>" alt="Logo" class="app-logo">
        <?= $setting['nama_aplikasi'] ?? 'Aplikasi Arsip Digital' ?>
    </a>

    <div class="navbar-menu">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="kategori.php" class="<?= basename($_SERVER['PHP_SELF']) === 'kategori.php' ? 'active' : '' ?>">
            <i class="fas fa-tags"></i> Kategori
        </a>
        <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) === 'laporan.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-check"></i> Laporan
        </a>
        <a href="komentar.php" class="<?= basename($_SERVER['PHP_SELF']) === 'komentar.php' ? 'active' : '' ?>">
            <i class="fas fa-comment-dots"></i> Komentar
        </a>
        <a href="notifikasi.php" class="<?= basename($_SERVER['PHP_SELF']) === 'notifikasi.php' ? 'active' : '' ?>">
            <i class="fas fa-bell"></i> Notifikasi
        </a>
        <a href="aktivitas.php" class="<?= basename($_SERVER['PHP_SELF']) === 'aktivitas.php' ? 'active' : '' ?>">
            <i class="fas fa-history"></i> Aktivitas
        </a>
        <a href="setting.php" class="<?= basename($_SERVER['PHP_SELF']) === 'setting.php' ? 'active' : '' ?>">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
        <a href="../auth/logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">