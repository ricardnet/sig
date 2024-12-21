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
    <title>Edit Data Kesehatan - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Edit Data Kesehatan</h2>

        <form action="../../controllers/HealthController.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $health['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="citizen_nik" class="form-control" required 
                       pattern="[0-9]{16}" value="<?= htmlspecialchars($health['citizen_nik']) ?>">
                <div class="invalid-feedback">NIK harus 16 digit angka</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="citizen_name" class="form-control" required 
                       value="<?= htmlspecialchars($health['citizen_name']) ?>">
                <div class="invalid-feedback">Nama tidak boleh kosong</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi Kesehatan</label>
                <select name="health_condition" class="form-select" required>
                    <option value="sehat" <?= $health['health_condition'] == 'sehat' ? 'selected' : '' ?>>Sehat</option>
                    <option value="sakit_ringan" <?= $health['health_condition'] == 'sakit_ringan' ? 'selected' : '' ?>>Sakit Ringan</option>
                    <option value="sakit_sedang" <?= $health['health_condition'] == 'sakit_sedang' ? 'selected' : '' ?>>Sakit Sedang</option>
                    <option value="sakit_berat" <?= $health['health_condition'] == 'sakit_berat' ? 'selected' : '' ?>>Sakit Berat</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="rawat_jalan" <?= $health['status'] == 'rawat_jalan' ? 'selected' : '' ?>>Rawat Jalan</option>
                    <option value="rawat_inap" <?= $health['status'] == 'rawat_inap' ? 'selected' : '' ?>>Rawat Inap</option>
                    <option value="isolasi_mandiri" <?= $health['status'] == 'isolasi_mandiri' ? 'selected' : '' ?>>Isolasi Mandiri</option>
                    <option value="sembuh" <?= $health['status'] == 'sembuh' ? 'selected' : '' ?>>Sembuh</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="health_description" class="form-control" rows="3"><?= htmlspecialchars($health['health_description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi pada Peta</label>
                <div id="map" style="height: 400px;" class="mb-2"></div>
                <input type="hidden" name="latitude" id="latitude" value="<?= $health['latitude'] ?>" required>
                <input type="hidden" name="longitude" id="longitude" value="<?= $health['longitude'] ?>" required>
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
        var map = L.map('map').setView([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add initial marker
        var marker = L.marker([<?= $health['latitude'] ?>, <?= $health['longitude'] ?>]).addTo(map);

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
