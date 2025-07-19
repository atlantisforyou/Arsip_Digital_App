<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$logs = $conn->query("SELECT a.*, u.username 
    FROM activity_logs a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Pengguna</title>
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

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: var(--bronze-light);
            color: var(--twilight-dark);
            font-weight: 600;
            border-bottom: 2px solid var(--bronze-medium);
            position: sticky;
            top: 0;
        }

        .table tbody tr:nth-child(even) {
            background-color: rgba(218, 160, 109, 0.05);
        }

        .table tbody tr:hover {
            background-color: rgba(218, 160, 109, 0.1);
        }

        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
            border-color: var(--bronze-light);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--bronze-medium);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .activity-message {
            max-width: 500px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .timestamp {
            color: var(--twilight-light);
            font-size: 0.875rem;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .table td, .table th {
                padding: 0.75rem;
            }
            
            .activity-message {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-history me-2"></i>Log Aktivitas Pengguna
        </h4>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($log['username'] ?? 'T', 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($log['username'] ?? 'Tidak diketahui') ?>
                            </div>
                        </td>
                        <td class="activity-message"><?= htmlspecialchars($log['activity']) ?></td>
                        <td class="timestamp"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
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