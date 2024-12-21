<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'];
            
            // Verify current password
            $user = $this->userModel->getUserById($userId);
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = "Password saat ini salah!";
                header("Location: ../views/profile/index.php");
                exit();
            }

            $userData = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone_number' => $_POST['phone_number'],
                'address' => $_POST['address']
            ];

            // Update password if provided
            if (!empty($_POST['new_password'])) {
                if ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok!";
                    header("Location: ../views/profile/index.php");
                    exit();
                }
                $userData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }

            try {
                $result = $this->userModel->updateUser($userId, $userData);
                if ($result) {
                    $_SESSION['success'] = "Profil berhasil diperbarui";
                } else {
                    throw new Exception("Gagal memperbarui profil");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/profile/index.php");
            exit();
        }
    }

    public function getUserData() {
        if (isset($_SESSION['user_id'])) {
            $userData = $this->userModel->getUserById($_SESSION['user_id']);
            unset($userData['password']); // Remove sensitive data
            return $userData;
        }
        return null;
    }
}

// Handle requests
if (isset($_POST['action']) || isset($_GET['action'])) {
    $user = new UserController($db);
    $action = $_POST['action'] ?? $_GET['action'];

    switch ($action) {
        case 'update_profile':
            $user->updateProfile();
            break;
        case 'get_data':
            header('Content-Type: application/json');
            echo json_encode($user->getUserData());
            exit();
        default:
            header("Location: ../views/profile/index.php");
            exit();
    }
}
?> 