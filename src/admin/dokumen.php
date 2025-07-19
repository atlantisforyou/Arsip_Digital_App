<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

$success = $error = '';

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $get = $conn->query("SELECT file_name FROM documents WHERE id = $id")->fetch_assoc();
    if ($get) {
        $path = '../public/uploads/dokumen/' . $get['file_name'];
        if (file_exists($path)) unlink($path);
        $conn->query("DELETE FROM documents WHERE id = $id");
        $success = 'Dokumen berhasil dihapus.';
    } else {
        $error = 'Dokumen tidak ditemukan.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $uploaded_by = $_SESSION['user_id'];
    $file = $_FILES['file'];

    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $ext;
        $file_path = '../public/uploads/dokumen/' . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO documents (event_id, file_name, file_type, uploaded_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $event_id, $file_name, $ext, $uploaded_by);
            $stmt->execute();
            $success = 'Dokumen berhasil diunggah.';
        } else {
            $error = 'Gagal memindahkan file.';
        }
    } else {
        $error = 'Terjadi kesalahan pada file.';
    }
}

$events = $conn->query("SELECT id, title FROM events ORDER BY id DESC");
$docs = $conn->query("SELECT d.*, e.title FROM documents d JOIN events e ON d.event_id = e.id ORDER BY d.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dokumen</title>
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

        .btn-danger {
            background-color: #c75d5d;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-danger:hover {
            background-color: #a74242;
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

        .file-link {
            color: var(--bronze-dark);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .file-link:hover {
            color: var(--bronze-medium);
            text-decoration: underline;
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
    <?php include '../includes/header_admin.php'; ?>

    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-file-alt me-2"></i>Manajemen Dokumen
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h5 class="section-title">Unggah Dokumen Baru</h5>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Acara/Kegiatan</label>
                        <select name="event_id" class="form-control" required>
                            <option value="">-- Pilih Acara --</option>
                            <?php while ($e = $events->fetch_assoc()): ?>
                                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">File Dokumen</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>Unggah Dokumen
                </button>
            </form>
        </div>

        <h5 class="section-title">Daftar Dokumen</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>Nama File</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($d = $docs->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['title']) ?></td>
                        <td>
                            <a href="../public/uploads/dokumen/<?= $d['file_name'] ?>" target="_blank" class="file-link">
                                <i class="fas fa-file me-2"></i><?= $d['file_name'] ?>
                            </a>
                        </td>
                        <td class="text-center"><?= strtoupper($d['file_type']) ?></td>
                        <td class="text-center"><?= date('d M Y H:i', strtotime($d['uploaded_at'])) ?></td>
                        <td class="text-center">
                            <a href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Yakin ingin menghapus dokumen ini?')" class="btn btn-sm btn-danger">
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