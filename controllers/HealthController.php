<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Health.php';

class HealthController {
    private $db;
    private $healthModel;

    public function __construct($db) {
        $this->db = $db;
        $this->healthModel = new Health($db);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Validasi input
                if (empty($_POST['citizen_nik']) || empty($_POST['citizen_name']) || 
                    empty($_POST['health_condition']) || empty($_POST['status'])) {
                    throw new Exception("Semua field harus diisi");
                }

                // Validasi NIK
                if (!preg_match("/^[0-9]{16}$/", $_POST['citizen_nik'])) {
                    throw new Exception("NIK harus 16 digit angka");
                }

                $healthData = [
                    'citizen_nik' => $_POST['citizen_nik'],
                    'citizen_name' => $_POST['citizen_name'],
                    'health_condition' => $_POST['health_condition'],
                    'status' => $_POST['status'],
                    'health_description' => $_POST['health_description'],
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude']
                ];

                $result = $this->healthModel->createHealthRecord($healthData);
                if ($result) {
                    $_SESSION['success'] = "Data kesehatan berhasil ditambahkan";
                } else {
                    throw new Exception("Gagal menambahkan data kesehatan");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/health/index.php");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (!isset($_POST['id'])) {
                    throw new Exception("ID tidak valid");
                }

                $id = $_POST['id'];
                $healthData = [
                    'citizen_nik' => $_POST['citizen_nik'],
                    'citizen_name' => $_POST['citizen_name'],
                    'health_condition' => $_POST['health_condition'],
                    'status' => $_POST['status'],
                    'health_description' => $_POST['health_description'],
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude']
                ];

                $result = $this->healthModel->updateHealthRecord($id, $healthData);
                if ($result) {
                    $_SESSION['success'] = "Data kesehatan berhasil diperbarui";
                } else {
                    throw new Exception("Gagal memperbarui data kesehatan");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/health/index.php");
            exit();
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            try {
                $id = $_GET['id'];
                $result = $this->healthModel->deleteHealthRecord($id);
                if ($result) {
                    $_SESSION['success'] = "Data kesehatan berhasil dihapus";
                } else {
                    throw new Exception("Gagal menghapus data kesehatan");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/health/index.php");
            exit();
        }
    }

    public function getStatistics() {
        try {
            return $this->healthModel->getHealthStatistics();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

// Handle requests
if (isset($_POST['action']) || isset($_GET['action'])) {
    $health = new HealthController($db);
    $action = $_POST['action'] ?? $_GET['action'];

    switch ($action) {
        case 'create':
            $health->create();
            break;
        case 'update':
            $health->update();
            break;
        case 'delete':
            $health->delete();
            break;
        default:
            header("Location: ../views/health/index.php");
            exit();
    }
}
?> 