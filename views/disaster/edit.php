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
    <title>Edit Data Bencana - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Edit Data Bencana</h2>

        <form action="../../controllers/DisasterController.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $disaster['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">Jenis Bencana</label>
                <select name="disaster_type" class="form-select" required>
                    <option value="banjir" <?= $disaster['disaster_type'] == 'banjir' ? 'selected' : '' ?>>Banjir</option>
                    <option value="longsor" <?= $disaster['disaster_type'] == 'longsor' ? 'selected' : '' ?>>Longsor</option>
                    <option value="kebakaran" <?= $disaster['disaster_type'] == 'kebakaran' ? 'selected' : '' ?>>Kebakaran</option>
                    <option value="gempa" <?= $disaster['disaster_type'] == 'gempa' ? 'selected' : '' ?>>Gempa Bumi</option>
                    <option value="angin_kencang" <?= $disaster['disaster_type'] == 'angin_kencang' ? 'selected' : '' ?>>Angin Kencang</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat Keparahan</label>
                <select name="severity_level" class="form-select" required>
                    <option value="ringan" <?= $disaster['severity_level'] == 'ringan' ? 'selected' : '' ?>>Ringan</option>
                    <option value="sedang" <?= $disaster['severity_level'] == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                    <option value="berat" <?= $disaster['severity_level'] == 'berat' ? 'selected' : '' ?>>Berat</option>
                    <option value="sangat_berat" <?= $disaster['severity_level'] == 'sangat_berat' ? 'selected' : '' ?>>Sangat Berat</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" required 
                       value="<?= htmlspecialchars($disaster['location']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="aktif" <?= $disaster['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="ditangani" <?= $disaster['status'] == 'ditangani' ? 'selected' : '' ?>>Sedang Ditangani</option>
                    <option value="selesai" <?= $disaster['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($disaster['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi pada Peta</label>
                <div id="map" style="height: 400px;" class="mb-2"></div>
                <input type="hidden" name="latitude" id="latitude" value="<?= $disaster['latitude'] ?>" required>
                <input type="hidden" name="longitude" id="longitude" value="<?= $disaster['longitude'] ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add initial marker
        var marker = L.marker([<?= $disaster['latitude'] ?>, <?= $disaster['longitude'] ?>]).addTo(map);

        // Handle map click
        map.on('click', function(e) {
            map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
