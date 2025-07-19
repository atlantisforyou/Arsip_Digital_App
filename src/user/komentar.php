<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $comment = trim($_POST['comment']);

    if ($event_id && $comment) {
        $stmt = $conn->prepare("INSERT INTO comments (event_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $event_id, $user_id, $comment);
        $stmt->execute();
        $success = 'Komentar berhasil dikirim.';
    } else {
        $error = 'Form tidak boleh kosong.';
    }
}

$events = $conn->query("SELECT * FROM events ORDER BY id DESC");
$my_comments = $conn->query("SELECT c.*, e.title FROM comments c 
    JOIN events e ON c.event_id = e.id 
    WHERE c.user_id = $user_id ORDER BY c.created_at DESC");
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
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(139, 107, 77, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--twilight-bronze), var(--twilight-dark));
        color: white;
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        border-bottom: none;
    }
    
    .btn-primary {
        background-color: var(--twilight-bronze);
        border-color: var(--twilight-dark);
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background-color: var(--twilight-dark);
        transform: translateY(-2px);
    }
    
    .form-control {
        border: 1px solid var(--twilight-light);
        border-radius: 6px;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--twilight-bronze);
        box-shadow: 0 0 0 0.25rem rgba(139, 107, 77, 0.2);
    }
    
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%238B6B4D' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }
    
    .table {
        border-color: var(--twilight-light);
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: var(--twilight-bronze);
        color: white;
        border-bottom: 2px solid var(--twilight-dark);
        padding: 12px 15px;
    }
    
    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    .table tbody tr:nth-child(even) {
        background-color: rgba(212, 183, 143, 0.08);
    }
    
    .table tbody tr:hover {
        background-color: rgba(193, 154, 107, 0.12);
    }
    
    .alert {
        border-radius: 6px;
        padding: 12px 15px;
        border-left: 4px solid transparent;
    }
    
    .alert-success {
        background-color: rgba(40, 167, 69, 0.08);
        border-left-color: #28a745;
        color: #28a745;
    }
    
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.08);
        border-left-color: #dc3545;
        color: #dc3545;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--twilight-light);
        margin-bottom: 15px;
    }
    
    label {
        font-weight: 500;
        color: var(--twilight-dark);
        margin-bottom: 8px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-comment-alt me-2"></i> Berikan Komentar Kegiatan
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="event_id" class="form-label">Pilih Kegiatan</label>
                            <select name="event_id" id="event_id" class="form-control form-select" required>
                                <option value="" selected disabled>-- Pilih Kegiatan --</option>
                                <?php while ($e = $events->fetch_assoc()): ?>
                                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">Komentar Anda</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" 
                                    placeholder="Tuliskan komentar Anda tentang kegiatan ini..." required></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Komentar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i> Riwayat Komentar Anda
                </div>
                <div class="card-body p-0">
                    <?php if ($my_comments->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Kegiatan</th>
                                        <th>Komentar</th>
                                        <th width="15%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; while ($c = $my_comments->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($c['title']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
                                        <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="far fa-comment-dots"></i>
                            <h5 class="mt-3 mb-2">Belum Ada Komentar</h5>
                            <p class="text-muted">Anda belum memberikan komentar untuk kegiatan apapun</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>