<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM reports WHERE id = $id");
    $success = 'Laporan berhasil dihapus.';
}

$laporan = $conn->query("SELECT r.*, u.username 
    FROM reports r 
    LEFT JOIN users u ON r.user_id = u.id 
    ORDER BY r.report_date DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengguna</title>
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
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--bronze-light);
        }

        .alert {
            border-radius: 6px;
            border: none;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--bronze-light);
            color: var(--twilight-dark);
            font-weight: 600;
            border-bottom: 2px solid var(--bronze-medium);
        }

        .table tbody tr:nth-child(even) {
            background-color: rgba(218, 160, 109, 0.05);
        }

        .table tbody tr:hover {
            background-color: rgba(218, 160, 109, 0.1);
        }

        .table td, .table th {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: var(--bronze-light);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .report-content {
            max-width: 300px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .table td, .table th {
                padding: 0.5rem;
            }
            
            .report-content {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-flag me-2"></i>Laporan Pengguna
        </h4>

        <?php if (isset($success)): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Tanggal</th>
                        <th>Isi Laporan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $laporan->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? 'Anonim') ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['report_date'])) ?></td>
                        <td class="report-content"><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                        <td>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                <i class="fas fa-trash-alt me-1"></i>Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>