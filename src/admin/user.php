<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$success = $error = '';
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

if (isset($_GET['hapus'])) {
    $hapus_id = intval($_GET['hapus']);
    if ($_SESSION['user_id'] == $hapus_id) {
        $error = "Kamu tidak dapat menghapus akun sendiri.";
    } else {
        $conn->query("DELETE FROM users WHERE id = $hapus_id");
        $success = "User berhasil dihapus.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    if ($edit_id) {
        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $username, $email, $password, $role, $edit_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $email, $role, $edit_id);
        }
        if ($stmt->execute()) {
            $success = 'User berhasil diperbarui.';
        } else {
            $error = 'Gagal memperbarui user.';
        }
    } else {
        $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $cek->bind_param('s', $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = 'Username sudah digunakan.';
        } else {
            if ($password) {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $password, $role);
                if ($stmt->execute()) {
                    $success = 'User berhasil ditambahkan.';
                } else {
                    $error = 'Gagal menambahkan user.';
                }
            } else {
                $error = 'Password wajib diisi.';
            }
        }
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$edit_data = $edit_id ? $conn->query("SELECT * FROM users WHERE id = $edit_id")->fetch_assoc() : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
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
        }

        .btn-primary:hover {
            background-color: var(--bronze-medium);
        }

        .btn-secondary {
            background-color: var(--twilight-light);
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
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

        .action-buttons {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .card-form {
                padding: 1rem;
            }
            
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h4 class="page-title">
            <i class="fas fa-users-cog me-2"></i>Manajemen Pengguna
        </h4>

        <?php if ($success): ?>
        <div class="alert alert-success mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h5 class="mb-4"><?= $edit_id ? 'Edit Pengguna' : 'Tambah Pengguna Baru' ?></h5>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required 
                            value="<?= htmlspecialchars($edit_data['username'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                            value="<?= htmlspecialchars($edit_data['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <?= $edit_id ? '(Kosongkan jika tidak diubah)' : '' ?></label>
                        <input type="password" name="password" class="form-control" <?= $edit_id ? '' : 'required' ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="user" <?= (isset($edit_data['role']) && $edit_data['role'] === 'user') ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= (isset($edit_data['role']) && $edit_data['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i><?= $edit_id ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($edit_id): ?>
                    <a href="user.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h5 class="mb-3">Daftar Pengguna</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= ucfirst($u['role']) ?></td>
                        <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td class="action-buttons">
                            <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <?php if ($_SESSION['user_id'] != $u['id']): ?>
                            <a href="?hapus=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">
                                <i class="fas fa-trash-alt me-1"></i>Hapus
                            </a>
                            <?php endif; ?>
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