<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $message = trim($_POST['message']);

    if ($user_id && $message) {
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        if ($stmt->execute()) {
            $success = "Notifikasi berhasil dikirim.";
        } else {
            $error = "Gagal mengirim notifikasi.";
        }
    } else {
        $error = "Form tidak boleh kosong.";
    }
}

$users = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
$notif = $conn->query("SELECT n.*, u.username FROM notifications n 
    LEFT JOIN users u ON n.user_id = u.id 
    ORDER BY n.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Sistem</title>
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

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-read {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-unread {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .timestamp {
            white-space: nowrap;
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
            <i class="fas fa-bell me-2"></i>Notifikasi Sistem
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h5 class="section-title">Kirim Notifikasi</h5>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pilih Pengguna</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Pilih Pengguna --</option>
                            <?php while ($u = $users->fetch_assoc()): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Pesan Notifikasi</label>
                        <input type="text" name="message" class="form-control" placeholder="Tulis pesan notifikasi" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Kirim
                </button>
            </form>
        </div>

        <h5 class="section-title">Daftar Notifikasi</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while ($n = $notif->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($n['username'] ?? 'System') ?></td>
                        <td><?= htmlspecialchars($n['message']) ?></td>
                        <td>
                            <span class="status-badge <?= $n['is_read'] ? 'status-read' : 'status-unread' ?>">
                                <?= $n['is_read'] ? 'Sudah dibaca' : 'Belum dibaca' ?>
                            </span>
                        </td>
                        <td class="timestamp"><?= date('d M Y H:i', strtotime($n['created_at'])) ?></td>
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