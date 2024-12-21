<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/Disaster.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$disasterModel = new Disaster($db);
$disasters = $disasterModel->getAllDisasterData();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Bencana - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Bencana</h2>
            <a href="create.php" class="btn btn-primary">Tambah Data Bencana</a>
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
                        <th>Jenis Bencana</th>
                        <th>Tingkat Keparahan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Pelapor</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($disasters as $disaster): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($disaster['disaster_type']) ?></td>
                        <td><?= htmlspecialchars($disaster['severity_level']) ?></td>
                        <td><?= htmlspecialchars($disaster['location']) ?></td>
                        <td><?= htmlspecialchars($disaster['status']) ?></td>
                        <td><?= htmlspecialchars($disaster['reporter_name'] ?? 'Unknown') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($disaster['created_at'])) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $disaster['id'] ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="edit.php?id=<?= $disaster['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../../controllers/DisasterController.php?action=delete&id=<?= $disaster['id'] ?>" 
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

        // Add markers for each disaster
        <?php foreach($disasters as $disaster): ?>
        L.marker([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>])
         .bindPopup("<b><?= htmlspecialchars($disaster['disaster_type']) ?></b><br>Lokasi: <?= htmlspecialchars($disaster['location']) ?>")
         .addTo(map);
        <?php endforeach; ?>
    </script>
</body>
</html>
