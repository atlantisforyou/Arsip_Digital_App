<?php
session_start();
require_once 'config/koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: index.php");
            exit;
        }
    }
    $error = 'Username atau password salah!';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arsip Digital</title>
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
                radial-gradient(circle at 10% 20%, rgba(205, 127, 50, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(218, 160, 109, 0.05) 0%, transparent 20%);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(44, 36, 27, 0.08);
            border: 1px solid rgba(218, 160, 109, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h3 {
            color: var(--bronze-dark);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
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
        }

        .form-label {
            font-weight: 500;
            color: var(--twilight-medium);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-login {
            background-color: var(--bronze-dark);
            color: white;
            border: none;
            font-weight: 600;
            height: 48px;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background-color: var(--bronze-medium);
        }

        .input-icon {
            color: var(--bronze-medium);
            margin-right: 0.5rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--twilight-light);
        }

        .login-footer a {
            color: var(--bronze-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--twilight-light);
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--bronze-light);
        }

        .divider::before {
            margin-right: 1rem;
        }

        .divider::after {
            margin-left: 1rem;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }
            
            .login-header h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header mb-4">
                <h3>Selamat Datang</h3>
                <p>Masuk ke sistem Arsip Digital</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger mb-4"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-user input-icon"></i>
                        </span>
                        <input type="text" id="username" name="username" required 
                            class="form-control border-start-0" placeholder="Masukkan username"
                            autocomplete="username">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-lock input-icon"></i>
                        </span>
                        <input type="password" id="password" name="password" required 
                            class="form-control border-start-0" placeholder="Masukkan password"
                            autocomplete="current-password">
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </div>

                <div class="divider">atau</div>

                <div class="login-footer">
                    <p class="mb-2">Belum punya akun? <a href="register.php">Daftar disini</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>