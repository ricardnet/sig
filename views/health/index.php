<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/Health.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$healthModel = new Health($db);
$healthRecords = $healthModel->getAllHealthData();


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kesehatan - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Kesehatan Warga</h2>
            <a href="create.php" class="btn btn-primary">Tambah Data Kesehatan</a>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Kondisi Kesehatan</th>
                        <th>Status</th>
                        <th>Pelapor</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($healthRecords as $health): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($health['citizen_nik']) ?></td>
                        <td><?= htmlspecialchars($health['citizen_name']) ?></td>
                        <td><?= htmlspecialchars($health['health_condition']) ?></td>
                        <td><?= htmlspecialchars($health['status']) ?>


                    </td>
                        <td><?= htmlspecialchars($health['recorded_by'] ?? 'Unknown') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($health['created_at'])) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $health['id'] ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="edit.php?id=<?= $health['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../../controllers/HealthController.php?action=delete&id=<?= $health['id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([-6.2088, 106.8456], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for each health record
        <?php foreach($healthRecords as $health): ?>
        L.marker([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>])
         .bindPopup("<b><?= htmlspecialchars($health['citizen_name']) ?></b><br>Kondisi: <?= htmlspecialchars($health['health_condition']) ?>")
         .addTo(map);
        <?php endforeach; ?>
    </script>
</body>
</html>
