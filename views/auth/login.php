<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #000428 0%, #004e92 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            width: 90%;
            max-width: 380px;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header img {
            width: 70px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .login-header h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0, 78, 146, 0.2);
            border-color: #004e92;
        }

        .input-group {
            margin-bottom: 1.2rem;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #004e92;
        }

        .btn-login {
            background: linear-gradient(45deg, #000428, #004e92);
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #000428, #006eb8);
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #004e92;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success .bi {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Header -->
        <div class="login-header">
            <img src="../../assets/images/logo.png" alt="SIGAP DESA">
            <h4>SIGAP DESA</h4>
        </div>

        <!-- Alert Error -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Alert Success -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                <?= $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="../../controllers/AuthController.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="username" class="form-control" 
                       placeholder="Username" required>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" class="form-control" 
                       placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <!-- Register Link -->
        <div class="register-link">
            Belum punya akun? 
            <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                Daftar
            </a>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Daftar Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../../controllers/AuthController.php" method="POST">
                        <input type="hidden" name="action" value="register">
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            <input type="text" name="fullname" class="form-control" 
                                   placeholder="Nama Lengkap" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="username" class="form-control" 
                                   placeholder="Username" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="Email" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <select name="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="public">Masyarakat</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Password" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="Konfirmasi Password" required>
                        </div>

                        <button type="submit" class="btn btn-login">
                            <i class="bi bi-person-plus me-2"></i>Daftar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>