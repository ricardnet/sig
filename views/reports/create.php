<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $type = $_POST['type']; // health atau disaster
        $description = $_POST['description'];
        $location = $_POST['location'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        
        if ($type == 'health') {
            $stmt = $db->prepare("
                INSERT INTO health_records (disease_type, description, location, latitude, longitude, recorded_by) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$_POST['disease_type'], $description, $location, $latitude, $longitude, $_SESSION['user_id']]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO disaster_records (disaster_type, description, location, latitude, longitude, recorded_by) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$_POST['disaster_type'], $description, $location, $latitude, $longitude, $_SESSION['user_id']]);
        }
        
        $_SESSION['success'] = "Laporan berhasil dikirim!";
        header("Location: my-reports.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal mengirim laporan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Buat Laporan Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Jenis Laporan</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Pilih Jenis Laporan</option>
                                    <option value="health">Kesehatan</option>
                                    <option value="disaster">Bencana</option>
                                </select>
                            </div>

                            <div id="healthFields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Penyakit</label>
                                    <input type="text" name="disease_type" class="form-control">
                                </div>
                            </div>

                            <div id="disasterFields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Bencana</label>
                                    <input type="text" name="disaster_type" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Lokasi di Peta</label>
                                <div id="map" style="height: 300px;"></div>
                                <input type="hidden" name="latitude" id="latitude" required>
                                <input type="hidden" name="longitude" id="longitude" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Laporan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Form fields toggle
        document.querySelector('select[name="type"]').addEventListener('change', function() {
            document.getElementById('healthFields').style.display = this.value === 'health' ? 'block' : 'none';
            document.getElementById('disasterFields').style.display = this.value === 'disaster' ? 'block' : 'none';
        });

        // Initialize map
        var map = L.map('map').setView([-6.2088, 106.8456], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
</body>
</html> 