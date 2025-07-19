<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $file = $_FILES['file'];
    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $ext;
        $tujuan = '../public/uploads/dokumen/' . $file_name;

        if (move_uploaded_file($file['tmp_name'], $tujuan)) {
            $stmt = $conn->prepare("INSERT INTO documents (event_id, file_name, file_type, uploaded_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $event_id, $file_name, $ext, $user_id);
            $stmt->execute();
            $success = 'Dokumen berhasil diunggah.';
        } else {
            $error = 'Gagal upload.';
        }
    }
}

$events = $conn->query("SELECT id, title FROM events ORDER BY id DESC");
$my_docs = $conn->query("SELECT d.*, e.title FROM documents d 
    JOIN events e ON d.event_id = e.id 
    WHERE uploaded_by = $user_id ORDER BY d.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
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
            margin: 2rem 0 1rem;
        }

        .upload-card {
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
            padding: 0.75rem 1rem;
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
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
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

        .file-icon {
            color: var(--bronze-medium);
            margin-right: 0.5rem;
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
            
            .upload-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-file-upload me-2"></i>Upload Dokumen
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="upload-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Kegiatan</label>
                    <select name="event_id" class="form-control" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <?php while ($e = $events->fetch_assoc()): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">File Dokumen</label>
                    <input type="file" name="file" class="form-control" required>
                    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX (Maks. 5MB)</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>Unggah Dokumen
                </button>
            </form>
        </div>

        <h5 class="section-title">Dokumen Anda</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Kegiatan</th>
                        <th width="35%">File</th>
                        <th width="15%">Tipe</th>
                        <th width="20%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($my_docs->num_rows > 0): ?>
                        <?php $no = 1; while ($d = $my_docs->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($d['title']) ?></td>
                            <td>
                                <a href="../public/uploads/dokumen/<?= $d['file_name'] ?>" target="_blank" class="file-link">
                                    <i class="fas fa-file-alt file-icon"></i><?= $d['file_name'] ?>
                                </a>
                            </td>
                            <td><?= strtoupper($d['file_type']) ?></td>
                            <td class="timestamp"><?= date('d M Y', strtotime($d['uploaded_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada dokumen</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>