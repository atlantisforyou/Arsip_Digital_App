<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$events = $conn->query("SELECT e.*, u.username 
    FROM events e 
    LEFT JOIN users u ON e.created_by = u.id 
    ORDER BY e.created_at DESC");

function get_categories($event_id, $conn) {
    $cat = $conn->query("SELECT c.name FROM event_categories ec 
                        JOIN categories c ON ec.category_id = c.id 
                        WHERE ec.event_id = $event_id");
    $list = [];
    while ($c = $cat->fetch_assoc()) {
        $list[] = $c['name'];
    }
    return implode(', ', $list);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kegiatan</title>
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

        .event-title {
            font-weight: 500;
            color: var(--bronze-dark);
        }

        .event-description {
            max-width: 300px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .category-badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            background-color: var(--bronze-light);
            color: var(--twilight-dark);
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.1rem;
        }

        .timestamp {
            color: var(--twilight-light);
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .creator-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .creator-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--bronze-medium);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .table td, .table th {
                padding: 0.75rem;
            }
            
            .event-description {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-calendar-alt me-2"></i>Daftar Kegiatan
        </h4>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Judul</th>
                        <th width="25%">Deskripsi</th>
                        <th width="8%">Tahun</th>
                        <th width="15%">Kategori</th>
                        <th width="12%">Dibuat oleh</th>
                        <th width="10%">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($e = $events->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="event-title"><?= htmlspecialchars($e['title']) ?></td>
                        <td class="event-description"><?= htmlspecialchars($e['description']) ?></td>
                        <td><?= $e['event_year'] ?></td>
                        <td>
                            <?php 
                            $categories = explode(', ', get_categories($e['id'], $conn));
                            foreach ($categories as $category): 
                                if (!empty($category)):
                            ?>
                                <span class="category-badge"><?= htmlspecialchars($category) ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </td>
                        <td>
                            <div class="creator-info">
                                <div class="creator-avatar">
                                    <?= strtoupper(substr($e['username'] ?? '-', 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($e['username'] ?? '-') ?>
                            </div>
                        </td>
                        <td class="timestamp"><?= date('d M Y', strtotime($e['created_at'])) ?></td>
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