<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

checkLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        #map { height: 80vh; }
        .input-panel {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 300px;
        }
        .form-floating { margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container-fluid mt-3">
        <div class="position-relative">
            <div id="map"></div>

            <!-- Panel Input -->
            <div class="input-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Tambah Laporan</h5>
                    <button type="button" class="btn-close" onclick="togglePanel()"></button>
                </div>

                <form id="reportForm" method="POST" action="save_report.php">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="reportType" name="type" required>
                            <option value="">Pilih jenis laporan</option>
                            <option value="health">Kesehatan</option>
                            <option value="disaster">Bencana</option>
                        </select>
                        <label>Jenis Laporan</label>
                    </div>

                    <!-- Field untuk laporan kesehatan -->
                    <div id="healthFields" style="display:none;">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="diseaseType" name="disease_type">
                            <label>Jenis Penyakit</label>
                        </div>
                    </div>

                    <!-- Field untuk laporan bencana -->
                    <div id="disasterFields" style="display:none;">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="disasterType" name="disaster_type">
                            <label>Jenis Bencana</label>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="description" name="description" style="height: 100px" required></textarea>
                        <label>Deskripsi</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="location" name="location" required>
                        <label>Lokasi (RT/RW)</label>
                    </div>

                    <input type="hidden" id="latitude" name="latitude" required>
                    <input type="hidden" id="longitude" name="longitude" required>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send"></i> Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-6.9236, 110.7767], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker;
        
        // Event click pada peta
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            
            // Update koordinat di form
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            
            // Tampilkan panel input
            document.querySelector('.input-panel').style.display = 'block';
        });

        // Toggle fields berdasarkan jenis laporan
        document.getElementById('reportType').addEventListener('change', function() {
            document.getElementById('healthFields').style.display = 
                this.value === 'health' ? 'block' : 'none';
            document.getElementById('disasterFields').style.display = 
                this.value === 'disaster' ? 'block' : 'none';
        });

        // Toggle panel input
        function togglePanel() {
            var panel = document.querySelector('.input-panel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
