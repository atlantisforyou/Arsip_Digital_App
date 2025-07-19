<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO reports (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $content);
        if ($stmt->execute()) {
            $success = 'Laporan berhasil dikirim.';
        } else {
            $error = 'Gagal mengirim laporan.';
        }
    } else {
        $error = 'Isi laporan tidak boleh kosong.';
    }
}

$laporan = $conn->query("SELECT * FROM reports WHERE user_id = $user_id ORDER BY report_date DESC");
?>

<style>
    :root {
        --twilight-bronze: #8B6B4D;
        --twilight-light: #D4B78F;
        --twilight-dark: #5A4A3A;
        --twilight-accent: #C19A6B;
        --twilight-bg: #F5EFE6;
    }
    
    body {
        background-color: var(--twilight-bg);
        color: var(--twilight-dark);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(139, 107, 77, 0.1);
        margin-bottom: 2rem;
    }
    
    .card-header {
        background-color: var(--twilight-bronze);
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .btn-primary {
        background-color: var(--twilight-bronze);
        border-color: var(--twilight-dark);
    }
    
    .btn-primary:hover {
        background-color: var(--twilight-dark);
        border-color: var(--twilight-dark);
    }
    
    textarea.form-control {
        border: 1px solid var(--twilight-light);
        border-radius: 6px;
        padding: 12px;
        transition: border-color 0.3s;
    }
    
    textarea.form-control:focus {
        border-color: var(--twilight-bronze);
        box-shadow: 0 0 0 0.25rem rgba(139, 107, 77, 0.25);
    }
    
    .table {
        border-color: var(--twilight-light);
    }
    
    .table thead th {
        background-color: var(--twilight-bronze);
        color: white;
        border-color: var(--twilight-dark);
    }
    
    .table tbody tr:nth-child(even) {
        background-color: rgba(212, 183, 143, 0.1);
    }
    
    .table tbody tr:hover {
        background-color: rgba(193, 154, 107, 0.15);
    }
    
    .alert {
        border-radius: 6px;
    }
    
    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        border-color: rgba(40, 167, 69, 0.3);
        color: #28a745;
    }
    
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #dc3545;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Laporan Kegiatan
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="content" class="form-label">Deskripsi Kegiatan</label>
                            <textarea name="content" id="content" rows="5" class="form-control" 
                                    placeholder="Tuliskan detail laporan kegiatan Anda di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Laporan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i> Riwayat Laporan Anda
                </div>
                <div class="card-body">
                    <?php if ($laporan->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Tanggal</th>
                                        <th>Isi Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; while ($r = $laporan->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d M Y H:i', strtotime($r['report_date'])) ?></td>
                                        <td><?= nl2br(htmlspecialchars($r['content'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada laporan yang dikirim</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>