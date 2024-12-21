<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/Health.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$healthModel = new Health($db);
$health = $healthModel->getHealthById($_GET['id']);

if(!$health) {
    $_SESSION['error'] = "Data kesehatan tidak ditemukan";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Data Kesehatan - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Data Kesehatan</h2>
            <div>
                <a href="edit.php?id=<?= $health['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Warga</h5>
                        <table class="table">
                            <tr>
                                <th width="30%">NIK</th>
                                <td><?= htmlspecialchars($health['citizen_nik']) ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?= htmlspecialchars($health['citizen_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td><?= htmlspecialchars($health['health_condition']) ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?= htmlspecialchars($health['status']) ?></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td><?= nl2br(htmlspecialchars($health['health_description'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pelaporan</h5>
                        <table class="table">
                            <tr>
                                <th width="30%">Pelapor</th>
                                <td><?= htmlspecialchars($health['reporter_name'] ?? 'Unknown') ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td><?= date('d/m/Y H:i', strtotime($health['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th>Update</th>
                                <td><?= date('d/m/Y H:i', strtotime($health['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi</h5>
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add marker
        L.marker([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>])
         .bindPopup("<b><?= htmlspecialchars($health['citizen_name']) ?></b><br>Kondisi: <?= htmlspecialchars($health['health_condition']) ?>")
         .addTo(map);
    </script>
</body>
</html> 