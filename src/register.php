<?php
session_start();
require_once 'config/koneksi.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Username sudah terdaftar.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $username, $email, $hashed, $role);

            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Gagal registrasi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Arsip Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-dark);
            padding: 20px;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(205, 127, 50, 0.08) 0%, transparent 25%),
                radial-gradient(circle at 80% 70%, rgba(218, 160, 109, 0.08) 0%, transparent 25%);
        }

        .register-container {
            width: 100%;
            max-width: 480px;
        }

        .register-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(44, 36, 27, 0.1);
            border: 1px solid var(--bronze-light);
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h3 {
            color: var(--bronze-dark);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: var(--twilight-light);
            font-size: 0.9rem;
        }

        .form-control {
            background-color: var(--ivory);
            border: 1px solid var(--bronze-light);
            height: 48px;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--bronze-medium);
            box-shadow: 0 0 0 0.2rem rgba(205, 127, 50, 0.1);
            background-color: white;
        }

        .form-label {
            font-weight: 500;
            color: var(--twilight-medium);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-register {
            background-color: var(--bronze-dark);
            color: white;
            border: none;
            font-weight: 600;
            height: 48px;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            background-color: var(--bronze-medium);
        }

        .input-icon {
            color: var(--bronze-medium);
        }

        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--twilight-light);
        }

        .register-footer a {
            color: var(--bronze-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .password-strength {
            height: 4px;
            background: var(--ivory);
            margin-top: 0.5rem;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            background: var(--bronze-medium);
            transition: width 0.3s ease;
        }

        .input-group-text {
            background-color: var(--ivory);
            border: 1px solid var(--bronze-light);
            border-right: none;
            color: var(--bronze-medium);
        }

        .form-control.with-icon {
            border-left: none;
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 1.5rem;
            }
            
            .register-header h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header mb-4">
                <h3>Buat Akun Baru</h3>
                <p>Daftar untuk mengakses sistem Arsip Digital</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success mb-4"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger mb-4"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user input-icon"></i>
                        </span>
                        <input type="text" id="username" name="username" required 
                            class="form-control with-icon" placeholder="Masukkan username"
                            autocomplete="username">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope input-icon"></i>
                        </span>
                        <input type="email" id="email" name="email" required 
                            class="form-control with-icon" placeholder="Masukkan email"
                            autocomplete="email">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group mb-1">
                        <span class="input-group-text">
                            <i class="fas fa-lock input-icon"></i>
                        </span>
                        <input type="password" id="password" name="password" required 
                            class="form-control with-icon" placeholder="Buat password"
                            autocomplete="new-password">
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="password-strength-bar"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirm" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock input-icon"></i>
                        </span>
                        <input type="password" id="confirm" name="confirm" required 
                            class="form-control with-icon" placeholder="Ulangi password"
                            autocomplete="new-password">
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </button>
                </div>
            </form>

            <div class="register-footer mt-4">
                <p class="mb-0">Sudah punya akun? <a href="login.php">Login disini</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>