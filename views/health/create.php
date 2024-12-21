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
    <title>Tambah Data Kesehatan - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Tambah Data Kesehatan</h2>

        <form action="../../controllers/HealthController.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="create">
            
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="citizen_nik" class="form-control" required 
                       pattern="[0-9]{16}" title="NIK harus 16 digit angka">
                <div class="invalid-feedback">NIK harus 16 digit angka</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="citizen_name" class="form-control" required>
                <div class="invalid-feedback">Nama tidak boleh kosong</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi Kesehatan</label>
                <select name="health_condition" class="form-select" required>
                    <option value="">Pilih kondisi</option>
                    <option value="sehat">Sehat</option>
                    <option value="sakit ringan">Sakit Ringan</option>
                    <option value="sakit sedang">Sakit Sedang</option>
                    <option value="sakit berat">Sakit Berat</option>
                </select>
                <div class="invalid-feedback">Pilih kondisi kesehatan</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Pilih status</option>
                    <option value="rawat jalan">Rawat Jalan</option>
                    <option value="rawat inap">Rawat Inap</option>
                    <option value="isolasi mandiri">Isolasi Mandiri</option>
                    <option value="sembuh">Sembuh</option>
                </select>
                <div class="invalid-feedback">Pilih status perawatan</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="health_description" class="form-control" rows="3"></textarea>
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
