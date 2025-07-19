<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$id = $_SESSION['user_id'];
$jumlah_dokumen = $conn->query("SELECT COUNT(*) FROM documents WHERE uploaded_by=$id")->fetch_row()[0];
$jumlah_laporan = $conn->query("SELECT COUNT(*) FROM reports WHERE user_id=$id")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --twilight-dark: #2C241B;
            --twilight-medium: #5A4A3F;
            --twilight-light: #8A7B70;
            --bronze-dark: #CD7F32;
            --bronze-medium: #DAA06D;
            --bronze-light: #E6C7A8;
            --ivory: #F8F4E9;
            --text-dark: #2C241B;
            --text-light: #5A4A3F;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--ivory);
            color: var(--text-dark);
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-title {
            color: var(--bronze-dark);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .welcome-text {
            color: var(--twilight-medium);
            margin-bottom: 2rem;
        }

        .welcome-text b {
            color: var(--bronze-dark);
        }

        .stats-card {
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(44, 36, 27, 0.1);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--bronze-dark), var(--bronze-medium));
        }

        .stats-card.documents {
            border-left: 4px solid var(--bronze-dark);
        }

        .stats-card.reports {
            border-left: 4px solid var(--bronze-medium);
        }

        .stats-card h5 {
            color: var(--twilight-medium);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .stats-card h3 {
            color: var(--bronze-dark);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0;
        }

        .stats-icon {
            font-size: 2.5rem;
            color: var(--bronze-medium);
            opacity: 0.8;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1.5rem;
            }
            
            .stats-card {
                padding: 1.25rem;
            }
            
            .stats-card h3 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Pengguna
        </h4>
        
        <p class="welcome-text">Halo, <b><?= htmlspecialchars($_SESSION['username']) ?></b> 👋</p>

        <div class="row">
            <div class="col-md-6">
                <div class="stats-card documents">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>Dokumen Anda</h5>
                    <h3><?= $jumlah_dokumen ?></h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card reports">
                    <div class="stats-icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <h5>Laporan Anda</h5>
                    <h3><?= $jumlah_laporan ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>