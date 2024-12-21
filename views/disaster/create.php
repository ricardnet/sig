<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Bencana - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Tambah Data Bencana</h2>

        <form action="../../controllers/DisasterController.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="create">
            
            <div class="mb-3">
                <label class="form-label">Jenis Bencana</label>
                <select name="disaster_type" class="form-select" required>
                    <option value="">Pilih jenis bencana</option>
                    <option value="banjir">Banjir</option>
                    <option value="longsor">Longsor</option>
                    <option value="kebakaran">Kebakaran</option>
                    <option value="gempa">Gempa Bumi</option>
                    <option value="angin_kencang">Angin Kencang</option>
                </select>
                <div class="invalid-feedback">Pilih jenis bencana</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat Keparahan</label>
                <select name="severity_level" class="form-select" required>
                    <option value="">Pilih tingkat keparahan</option>
                    <option value="ringan">Ringan</option>
                    <option value="sedang">Sedang</option>
                    <option value="berat">Berat</option>
                    <option value="sangat_berat">Sangat Berat</option>
                </select>
                <div class="invalid-feedback">Pilih tingkat keparahan</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" required>
                <div class="invalid-feedback">Lokasi tidak boleh kosong</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Pilih status</option>
                    <option value="aktif">Aktif</option>
                    <option value="ditangani">Sedang Ditangani</option>
                    <option value="selesai">Selesai</option>
                </select>
                <div class="invalid-feedback">Pilih status bencana</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Lokasi pada Peta</label>
                <div id="map" style="height: 400px;" class="mb-2"></div>
                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
                <div class="invalid-feedback">Pilih lokasi pada peta</div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([-6.2088, 106.8456], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        // Handle map click
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
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
