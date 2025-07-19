<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $year = $_POST['year'];
    $created_by = $_SESSION['user_id'];

    if ($title && $year) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_year, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $year, $created_by);
        if ($stmt->execute()) {
            $success = "Kegiatan berhasil ditambahkan.";
        } else {
            $error = "Gagal menyimpan kegiatan.";
        }
    } else {
        $error = "Judul dan tahun wajib diisi.";
    }
}

$events = $conn->query("SELECT e.*, u.username FROM events e LEFT JOIN users u ON e.created_by = u.id ORDER BY e.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kegiatan</title>
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

        .card-form {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(44, 36, 27, 0.05);
            border-left: 4px solid var(--bronze-medium);
        }

        .form-label {
            font-weight: 500;
            color: var(--twilight-medium);
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: var(--ivory);
            border: 1px solid var(--bronze-light);
            border-radius: 6px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--bronze-medium);
            box-shadow: 0 0 0 0.2rem rgba(205, 127, 50, 0.1);
            background-color: white;
        }

        .btn-primary {
            background-color: var(--bronze-dark);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--bronze-medium);
        }

        .alert {
            border-radius: 6px;
            border: none;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .card-form {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-calendar-alt me-2"></i>Manajemen Kegiatan
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-form">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" min="2000" max="2100" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </form>
        </div>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tahun</th>
                        <th>Dibuat oleh</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($e = $events->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($e['title']) ?></td>
                        <td><?= htmlspecialchars($e['description']) ?></td>
                        <td><?= $e['event_year'] ?></td>
                        <td><?= $e['username'] ?? '-' ?></td>
                        <td><?= date('d M Y H:i', strtotime($e['created_at'])) ?></td>
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