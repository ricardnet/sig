<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/Health.php';
require_once '../../models/Disaster.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$healthModel = new Health($db);
$disasterModel = new Disaster($db);

// Mengambil data untuk statistik
$healthData = $healthModel->getAllHealthData();
$disasterData = $disasterModel->getAllDisasterData();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.05);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            padding: 1.5rem;
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-title {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
        }
        #map {
            height: 400px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard SIGAP Desa</h2>
            <span class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <!-- Total Kasus Kesehatan -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title text-white-50">Total Kasus Kesehatan</div>
                            <div class="stat-value"><?= count($healthData) ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bencana -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-danger text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title text-white-50">Total Bencana</div>
                            <div class="stat-value"><?= count($disasterData) ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kasus Aktif -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title text-white-50">Kasus Aktif</div>
                            <div class="stat-value"><?= count(array_filter($healthData, fn($h) => $h['status'] == 'aktif')) ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bencana Aktif -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-info text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title text-white-50">Bencana Aktif</div>
                            <div class="stat-value"><?= count(array_filter($disasterData, fn($d) => $d['status'] == 'aktif')) ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Peta Sebaran</h5>
                <div id="map"></div>
            </div>
        </div>

        <!-- Data Terbaru -->
        <div class="row">
            <!-- Kesehatan Terbaru -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kasus Kesehatan Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kondisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $latestHealth = array_slice($healthData, 0, 5);
                                    foreach($latestHealth as $health): 
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($health['citizen_name']) ?></td>
                                        <td><?= htmlspecialchars($health['health_condition']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $health['status'] == 'aktif' ? 'danger' : 'success' ?>">
                                                <?= htmlspecialchars($health['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bencana Terbaru -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bencana Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $latestDisaster = array_slice($disasterData, 0, 5);
                                    foreach($latestDisaster as $disaster): 
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($disaster['disaster_type']) ?></td>
                                        <td><?= htmlspecialchars($disaster['location']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $disaster['status'] == 'aktif' ? 'danger' : 'success' ?>">
                                                <?= htmlspecialchars($disaster['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([-6.9236, 110.7767], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for health cases
        <?php foreach($healthData as $health): ?>
        L.marker([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>], {
            icon: L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background-color: #0d6efd; width: 10px; height: 10px; border-radius: 50%;"></div>'
            })
        }).bindPopup('Kesehatan: <?= htmlspecialchars($health['citizen_name']) ?>')
        .addTo(map);
        <?php endforeach; ?>

        // Add markers for disasters
        <?php foreach($disasterData as $disaster): ?>
        L.marker([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>], {
            icon: L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background-color: #dc3545; width: 10px; height: 10px; border-radius: 50%;"></div>'
            })
        }).bindPopup('Bencana: <?= htmlspecialchars($disaster['disaster_type']) ?>')
        .addTo(map);
        <?php endforeach; ?>
    </script>
</body>
</html>