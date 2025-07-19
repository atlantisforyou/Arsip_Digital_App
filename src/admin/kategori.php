<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$success = $error = '';
if (isset($_POST['tambah_kategori'])) {
    $name = trim($_POST['name']);
    if ($name) {
        $cek = $conn->query("SELECT * FROM categories WHERE name = '$name'");
        if ($cek->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $success = 'Kategori berhasil ditambahkan.';
        } else {
            $error = 'Kategori sudah ada.';
        }
    }
}

if (isset($_POST['relasi_event'])) {
    $event_id = $_POST['event_id'];
    $category_id = $_POST['category_id'];
    $cek = $conn->query("SELECT * FROM event_categories WHERE event_id=$event_id AND category_id=$category_id");
    if ($cek->num_rows == 0) {
        $conn->query("INSERT INTO event_categories (event_id, category_id) VALUES ($event_id, $category_id)");
        $success = "Relasi event ke kategori berhasil.";
    } else {
        $error = "Relasi sudah ada.";
    }
}

$kategori = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$events = $conn->query("SELECT * FROM events ORDER BY id DESC");
$relasi = $conn->query("SELECT ec.*, e.title, c.name FROM event_categories ec 
    JOIN events e ON ec.event_id = e.id 
    JOIN categories c ON ec.category_id = c.id
    ORDER BY ec.event_id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori</title>
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

        .section-title {
            color: var(--bronze-dark);
            font-weight: 600;
            margin: 1.5rem 0 1rem;
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

        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-primary {
            background-color: var(--bronze-dark);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
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
            <i class="fas fa-tags me-2"></i>Manajemen Kategori
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h5 class="section-title">Tambah Kategori Baru</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>
                <button type="submit" name="tambah_kategori" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Simpan Kategori
                </button>
            </form>
        </div>

        <div class="card-form">
            <h5 class="section-title">Hubungkan Event dengan Kategori</h5>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilih Event</label>
                        <select name="event_id" class="form-control" required>
                            <option value="">-- Pilih Event --</option>
                            <?php foreach ($events as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilih Kategori</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" name="relasi_event" class="btn btn-primary">
                    <i class="fas fa-link me-2"></i>Simpan Relasi
                </button>
            </form>
        </div>

        <h5 class="section-title">Daftar Relasi Event & Kategori</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Judul Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($relasi as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>