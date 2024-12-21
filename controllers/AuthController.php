<?php
session_start();
require_once '../config/database.php';

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($data) {
        try {
            // Validasi data
            if (empty($_POST['fullname']) || empty($_POST['username']) || 
            empty($_POST['email']) || empty($_POST['password']) || 
            empty($_POST['confirm_password']) || empty($_POST['role'])) {
            throw new Exception("Semua field harus diisi!");
        }


            // Validasi password
            if ($data['password'] !== $data['confirm_password']) {
                throw new Exception("Konfirmasi password tidak cocok!");
            }

            // Cek username
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$data['username']]);
            if ($stmt->fetch()) {
                throw new Exception("Username sudah digunakan!");
            }

            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert user baru
            $query = "INSERT INTO users (full_name, username, email, password, role) 
                     VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
                $data['fullname'],
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['role']
            ]);

            if ($result) {
                // Set session untuk auto login
                $userId = $this->db->lastInsertId();
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $data['username'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['fullname'] = $data['fullname'];
                
                return true;
            }

            throw new Exception("Gagal mendaftarkan user!");

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['fullname'] = $user['fullname'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Login error: " . $e->getMessage());
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit();
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController($db);
    
    try {
        switch ($_POST['action']) {
            case 'register':
                if ($auth->register($_POST)) {
                    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                    header("Location: ../views/auth/login.php");
                    exit();
                }
                break;

            case 'login':
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    throw new Exception("Username dan password harus diisi!");
                }

                if ($auth->login($_POST['username'], $_POST['password'])) {
                    $_SESSION['success'] = "Login berhasil! Selamat datang " . $_SESSION['fullname'];
                    header("Location: ../views/dashboard/index.php");
                    exit();
                } else {
                    throw new Exception("Username atau password salah!");
                }
                break;

            case 'logout':
                $auth->logout();
                break;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../views/auth/login.php");
        exit();
    }
}
?>
