<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

checkLogin();

// Ambil laporan kesehatan user
$health_query = "SELECT * FROM health_records WHERE recorded_by = ? ORDER BY created_at DESC";
$health_stmt = $db->prepare($health_query);
$health_stmt->execute([$_SESSION['user_id']]);
$health_reports = $health_stmt->fetchAll();

// Ambil laporan bencana user
$disaster_query = "SELECT * FROM disaster_records WHERE recorded_by = ? ORDER BY created_at DESC";
$disaster_stmt = $db->prepare($disaster_query);
$disaster_stmt->execute([$_SESSION['user_id']]);
$disaster_reports = $disaster_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - SIGAP Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include '../layouts/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Laporan Saya</h4>
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Buat Laporan
            </a>
        </div>

        <!-- Laporan Kesehatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-heart-pulse-fill text-danger"></i> Laporan Kesehatan</h5>
            </div>
            <div class="card-body">
                <?php if (count($health_reports) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Penyakit</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($health_reports as $report): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($report['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($report['disease_type']) ?></td>
                                    <td><?= htmlspecialchars($report['location']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $report['status'] === 'Aktif' ? 'danger' : 
                                            ($report['status'] === 'Dalam Pemantauan' ? 'warning' : 'success') ?>">
                                            <?= $report['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada laporan kesehatan.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Laporan Bencana -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Laporan Bencana</h5>
            </div>
            <div class="card-body">
                <?php if (count($disaster_reports) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Bencana</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($disaster_reports as $report): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($report['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($report['disaster_type']) ?></td>
                                    <td><?= htmlspecialchars($report['location']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $report['status'] === 'Aktif' ? 'danger' : 
                                            ($report['status'] === 'Dalam Pemantauan' ? 'warning' : 'success') ?>">
                                            <?= $report['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada laporan bencana.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 