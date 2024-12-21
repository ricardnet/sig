<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Disaster.php';

class DisasterController {
    private $db;
    private $disasterModel;

    public function __construct($db) {
        $this->db = $db;
        $this->disasterModel = new Disaster($db);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Validasi input
                if (empty($_POST['disaster_type']) || empty($_POST['severity_level']) || 
                    empty($_POST['location']) || empty($_POST['status'])) {
                    throw new Exception("Semua field harus diisi");
                }

                $disasterData = [
                    'disaster_type' => $_POST['disaster_type'],
                    'severity_level' => $_POST['severity_level'],
                    'location' => $_POST['location'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude']
                ];

                $result = $this->disasterModel->createDisasterRecord($disasterData);
                if ($result) {
                    $_SESSION['success'] = "Data bencana berhasil ditambahkan";
                } else {
                    throw new Exception("Gagal menambahkan data bencana");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/disaster/index.php");
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
                $disasterData = [
                    'disaster_type' => $_POST['disaster_type'],
                    'severity_level' => $_POST['severity_level'],
                    'location' => $_POST['location'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude']
                ];

                $result = $this->disasterModel->updateDisasterRecord($id, $disasterData);
                if ($result) {
                    $_SESSION['success'] = "Data bencana berhasil diperbarui";
                } else {
                    throw new Exception("Gagal memperbarui data bencana");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/disaster/index.php");
            exit();
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            try {
                $id = $_GET['id'];
                $result = $this->disasterModel->deleteDisasterRecord($id);
                if ($result) {
                    $_SESSION['success'] = "Data bencana berhasil dihapus";
                } else {
                    throw new Exception("Gagal menghapus data bencana");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header("Location: ../views/disaster/index.php");
            exit();
        }
    }

    public function getStatistics() {
        try {
            return $this->disasterModel->getDisasterStatistics();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

// Handle requests
if (isset($_POST['action']) || isset($_GET['action'])) {
    $disaster = new DisasterController($db);
    $action = $_POST['action'] ?? $_GET['action'];

    switch ($action) {
        case 'create':
            $disaster->create();
            break;
        case 'update':
            $disaster->update();
            break;
        case 'delete':
            $disaster->delete();
            break;
        default:
            header("Location: ../views/disaster/index.php");
            exit();
    }
}
?>
