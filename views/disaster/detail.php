<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/Disaster.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$disasterModel = new Disaster($db);
$disaster = $disasterModel->getDisasterById($_GET['id']);

if(!$disaster) {
    $_SESSION['error'] = "Data bencana tidak ditemukan";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bencana - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Bencana</h2>
            <div>
                <a href="edit.php?id=<?= $disaster['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Bencana</h5>
                        <table class="table">
                            <tr>
                                <th width="30%">Jenis Bencana</th>
                                <td><?= htmlspecialchars($disaster['disaster_type']) ?></td>
                            </tr>
                            <tr>
                                <th>Tingkat Keparahan</th>
                                <td><?= htmlspecialchars($disaster['severity_level']) ?></td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td><?= htmlspecialchars($disaster['location']) ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?= htmlspecialchars($disaster['status']) ?></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td><?= nl2br(htmlspecialchars($disaster['description'])) ?></td>
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
                                <td><?= htmlspecialchars($disaster['reporter_name'] ?? 'Unknown') ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lapor</th>
                                <td><?= date('d/m/Y H:i', strtotime($disaster['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th>Terakhir Update</th>
                                <td><?= date('d/m/Y H:i', strtotime($disaster['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi Bencana</h5>
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
        var map = L.map('map').setView([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add marker
        L.marker([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>])
         .bindPopup("<b><?= htmlspecialchars($disaster['disaster_type']) ?></b><br>Lokasi: <?= htmlspecialchars($disaster['location']) ?>")
         .addTo(map);
    </script>
</body>
</html> 