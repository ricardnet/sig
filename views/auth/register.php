<?php
session_start();
require_once '../../config/database.php';

if(isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

// Buat koneksi database
try {
    $db = new PDO(
        "mysql:host=localhost;dbname=sig_desa", 
        "root", 
        ""
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validasi input
        if (empty($_POST['fullname']) || empty($_POST['username']) || 
            empty($_POST['email']) || empty($_POST['password']) || 
            empty($_POST['confirm_password']) || empty($_POST['role'])) {
            throw new Exception("Semua field harus diisi!");
        }

        // Validasi password match
        if ($_POST['password'] !== $_POST['confirm_password']) {
            throw new Exception("Konfirmasi password tidak cocok!");
        }

        // Cek username sudah ada atau belum
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        if ($stmt->fetch()) {
            throw new Exception("Username sudah digunakan!");
        }

        // Hash password
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user baru
        $stmt = $db->prepare("
            INSERT INTO users (full_name, username, email, password, role) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $_POST['fullname'],
            $_POST['username'],
            $_POST['email'],
            $hashedPassword,
            $_POST['role']
        ]);

        if ($result) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            throw new Exception("Gagal melakukan registrasi!");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SIGAP Desa</title>
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
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            max-width: 500px;
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

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header img {
            width: 70px;
            margin-bottom: 1rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
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

        .btn-register {
            background: linear-gradient(45deg, #000428, #004e92);
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #2c3e50;
        }

        .login-link a {
            color: #004e92;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="../../assets/images/logo.png" alt="SIGAP DESA">
            <h4>Daftar SIGAP DESA</h4>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person-badge"></i>
                </span>
                <input type="text" name="fullname" class="form-control" 
                       placeholder="Nama Lengkap" required
                       value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="username" class="form-control" 
                       placeholder="Username" required
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" class="form-control" 
                       placeholder="Email" required
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <select name="role" class="form-control" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="public" <?= (isset($_POST['role']) && $_POST['role'] == 'public') ? 'selected' : '' ?>>Masyarakat</option>
                </select>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" class="form-control" 
                       placeholder="Password" required>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" name="confirm_password" class="form-control" 
                       placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-register">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? 
            <a href="login.php">Login di sini</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
