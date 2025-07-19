<?php
require_once '../auth/cek_session.php';
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';
include '../includes/header_admin.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $val) {
        if ($key === 'simpan') continue;
        $stmt = $conn->prepare("UPDATE settings SET value=? WHERE name=?");
        $stmt->bind_param("ss", $val, $key);
        $stmt->execute();
    }

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $nama_logo = 'logo_' . time() . '.' . $ext;
        $tujuan = '../public/uploads/logo/' . $nama_logo;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $tujuan)) {
            $conn->query("UPDATE settings SET value='$nama_logo' WHERE name='logo'");
        } else {
            $error = 'Gagal upload logo.';
        }
    }

    $success = 'Pengaturan berhasil disimpan.';
}

$data = [];
$rows = $conn->query("SELECT * FROM settings");
while ($r = $rows->fetch_assoc()) {
    $data[$r['name']] = $r['value'];
}
?>

<style>
    :root {
        --twilight-dark: #2A1A1F;
        --twilight-bronze: #8B5A2B;
        --twilight-light: #D4A59A;
        --twilight-accent: #C77966;
        --twilight-text: #F8F1E5;
    }
    
    body {
        background-color: #F9F5F0;
        color: #333;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .card-header {
        background-color: var(--twilight-bronze);
        color: white;
        font-weight: 600;
        padding: 1.2rem;
        border-bottom: none;
    }
    
    .form-control, .form-control:focus {
        border-color: #ddd;
        box-shadow: none;
    }
    
    .form-control:focus {
        border-color: var(--twilight-accent);
    }
    
    .btn-primary {
        background-color: var(--twilight-bronze);
        border-color: var(--twilight-bronze);
        padding: 0.5rem 1.75rem;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: var(--twilight-dark);
        border-color: var(--twilight-dark);
    }
    
    .logo-preview {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
        background-color: white;
        margin-bottom: 15px;
    }
    
    .logo-preview img {
        max-height: 120px;
        display: block;
        margin: 0 auto;
    }
    
    .alert {
        border-radius: 8px;
    }
    
    .section-title {
        color: var(--twilight-bronze);
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background-color: var(--twilight-accent);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="section-title">Pengaturan Aplikasi</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cog me-2"></i> Informasi Umum
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label">Nama Aplikasi</label>
                            <input type="text" name="nama_aplikasi" value="<?= htmlspecialchars($data['nama_aplikasi'] ?? '') ?>" class="form-control" placeholder="Masukkan nama aplikasi">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Organisasi</label>
                            <input type="text" name="organisasi" value="<?= htmlspecialchars($data['organisasi'] ?? '') ?>" class="form-control" placeholder="Masukkan nama organisasi">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi aplikasi"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
                        </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-image me-2"></i> Logo Aplikasi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <?php if (!empty($data['logo'])): ?>
                            <div class="logo-preview text-center mb-3">
                                <p class="text-muted small mb-2">Logo Saat Ini</p>
                                <img src="../public/uploads/logo/<?= htmlspecialchars($data['logo']) ?>" class="img-fluid" alt="Logo Aplikasi">
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Tidak ada logo yang diunggah
                            </div>
                        <?php endif; ?>
                        
                        <label class="form-label">Unggah Logo Baru</label>
                        <input type="file" name="logo" class="form-control">
                        <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <button name="simpan" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> Simpan Pengaturan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>