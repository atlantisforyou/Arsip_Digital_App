<?php
require_once '../auth/cek_session.php';
if ($_SESSION['role'] !== 'user') die('Akses ditolak.');
require_once '../config/koneksi.php';
include '../includes/header_user.php';

$user_id = $_SESSION['user_id'];

$conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");

$notif = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<style>
    :root {
        --twilight-bronze: #8B6B4D;
        --twilight-light: #D4B78F;
        --twilight-dark: #5A4A3A;
        --twilight-accent: #C19A6B;
        --twilight-bg: #F5EFE6;
        --twilight-success: #4A8B6B;
    }
    
    body {
        background-color: var(--twilight-bg);
        color: var(--twilight-dark);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .notification-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .notification-header {
        color: var(--twilight-dark);
        border-bottom: 2px solid var(--twilight-light);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .notification-header h4 {
        font-weight: 600;
        margin: 0;
    }
    
    .notification-count {
        background-color: var(--twilight-bronze);
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    
    .notification-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(139, 107, 77, 0.08);
        margin-bottom: 1rem;
        border-left: 4px solid var(--twilight-bronze);
        transition: all 0.3s ease;
    }
    
    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139, 107, 77, 0.15);
    }
    
    .notification-content {
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
    }
    
    .notification-icon {
        color: var(--twilight-bronze);
        font-size: 1.2rem;
        margin-right: 1rem;
        margin-top: 3px;
    }
    
    .notification-message {
        flex: 1;
    }
    
    .notification-message p {
        margin: 0;
        line-height: 1.5;
    }
    
    .notification-time {
        color: var(--twilight-light);
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .notification-time i {
        margin-right: 5px;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(139, 107, 77, 0.08);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--twilight-light);
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        color: var(--twilight-dark);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: var(--twilight-light);
    }
</style>

<div class="notification-container">
    <div class="notification-header">
        <h4><i class="fas fa-bell me-2"></i>Notifikasi Anda</h4>
        <?php if ($notif->num_rows > 0): ?>
            <div class="notification-count">
                <?= $notif->num_rows ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($notif->num_rows == 0): ?>
        <div class="empty-state">
            <i class="far fa-bell-slash"></i>
            <h5>Belum ada notifikasi</h5>
            <p>Anda tidak memiliki notifikasi saat ini</p>
        </div>
    <?php else: ?>
        <div class="notification-list">
            <?php $no=1; while ($n = $notif->fetch_assoc()): ?>
                <div class="notification-card">
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="notification-message">
                            <p><?= htmlspecialchars($n['message']) ?></p>
                            <div class="notification-time">
                                <i class="far fa-clock"></i>
                                <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>