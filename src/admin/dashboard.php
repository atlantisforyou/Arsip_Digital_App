<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'admin') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$jml_user     = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$jml_event    = $conn->query("SELECT COUNT(*) FROM events")->fetch_row()[0];
$jml_dokumen  = $conn->query("SELECT COUNT(*) FROM documents")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            border-left: 4px solid var(--bronze-medium);
        }

        .welcome-card h4 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--bronze-dark);
        }

        .welcome-card p {
            color: var(--twilight-light);
            font-size: 1rem;
            margin-bottom: 0;
        }

        .username {
            color: var(--bronze-dark);
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(44, 36, 27, 0.1);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--bronze-dark), var(--bronze-medium));
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--bronze-medium);
        }

        .stat-card h5 {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            color: var(--twilight-medium);
        }

        .stat-card .stat-number {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0;
            color: var(--bronze-dark);
            line-height: 1;
        }

        .stat-card .card-footer {
            background: rgba(218, 160, 109, 0.05);
            border-top: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
        }

        .stat-card .card-footer a {
            color: var(--bronze-dark);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .stat-card .card-footer a:hover {
            color: var(--bronze-medium);
            text-decoration: underline;
        }

        .stat-card .card-footer i {
            margin-left: 8px;
        }

        .bg-pattern {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0.03;
            background-image: 
                radial-gradient(circle at 20% 30%, var(--bronze-dark) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, var(--bronze-light) 0%, transparent 20%);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    
    <div class="dashboard-container">
        <div class="welcome-card">
            <h4>Dashboard Admin</h4>
            <p>Selamat datang, <span class="username"><?= $_SESSION['username'] ?></span></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>Total Pengguna</h5>
                    <h3 class="stat-number"><?= $jml_user ?></h3>
                </div>
                <div class="card-footer">
                    <a href="user.php">
                        Kelola Pengguna <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>Total Kegiatan</h5>
                    <h3 class="stat-number"><?= $jml_event ?></h3>
                </div>
                <div class="card-footer">
                    <a href="event.php">
                        Kelola Kegiatan <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>Total Dokumen</h5>
                    <h3 class="stat-number"><?= $jml_dokumen ?></h3>
                </div>
                <div class="card-footer">
                    <a href="dokumen.php">
                        Kelola Dokumen <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>