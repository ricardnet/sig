<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $type = $_POST['type'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $current_time = date('Y-m-d H:i:s');
        
        if ($type == 'health') {
            $stmt = $db->prepare("
                INSERT INTO health_records (
                    disease_type, 
                    description, 
                    location, 
                    latitude, 
                    longitude, 
                    recorded_by,
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 'Aktif', ?)
            ");
            $stmt->execute([
                $_POST['disease_type'],
                $description,
                $location,
                $latitude,
                $longitude,
                $_SESSION['user_id'],
                $current_time
            ]);
            
            // Ambil data yang baru dimasukkan
            $lastId = $db->lastInsertId();
            $stmt = $db->prepare("
                SELECT h.*, u.fullname as reporter_name 
                FROM health_records h 
                LEFT JOIN users u ON h.recorded_by = u.id 
                WHERE h.id = ?
            ");
            $stmt->execute([$lastId]);
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['report_info'] = [
                'type' => 'health',
                'title' => 'Laporan Kesehatan Berhasil Ditambahkan',
                'data' => [
                    'Jenis Penyakit' => $report['disease_type'],
                    'Lokasi' => $report['location'],
                    'Deskripsi' => $report['description'],
                    'Status' => 'Aktif',
                    'Dilaporkan oleh' => $report['reporter_name'],
                    'Waktu' => date('d M Y H:i', strtotime($report['created_at']))
                ]
            ];
            
        } else {
            $stmt = $db->prepare("
                INSERT INTO disaster_records (
                    disaster_type, 
                    description, 
                    location, 
                    latitude, 
                    longitude, 
                    recorded_by,
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 'Aktif', ?)
            ");
            $stmt->execute([
                $_POST['disaster_type'],
                $description,
                $location,
                $latitude,
                $longitude,
                $_SESSION['user_id'],
                $current_time
            ]);
            
            // Ambil data yang baru dimasukkan
            $lastId = $db->lastInsertId();
            $stmt = $db->prepare("
                SELECT d.*, u.fullname as reporter_name 
                FROM disaster_records d 
                LEFT JOIN users u ON d.recorded_by = u.id 
                WHERE d.id = ?
            ");
            $stmt->execute([$lastId]);
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['report_info'] = [
                'type' => 'disaster',
                'title' => 'Laporan Bencana Berhasil Ditambahkan',
                'data' => [
                    'Jenis Bencana' => $report['disaster_type'],
                    'Lokasi' => $report['location'],
                    'Deskripsi' => $report['description'],
                    'Status' => 'Aktif',
                    'Dilaporkan oleh' => $report['reporter_name'],
                    'Waktu' => date('d M Y H:i', strtotime($report['created_at']))
                ]
            ];
        }
        
        $_SESSION['success'] = "Laporan berhasil dikirim!";
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal mengirim laporan: " . $e->getMessage();
    }
}

// Redirect kembali ke halaman peta dengan parameter
header("Location: index.php?show_info=1");
exit();
?> 