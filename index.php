<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP DESA - Sistem Informasi Geografis Pelaporan Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd20 0%, #0d6efd05 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            text-align: center;
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0,0,0,0.05);
            margin: 40px 0;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .btn-custom {
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
        .footer {
            background: white;
            padding: 20px 0;
            position: relative;
            margin-top: 50px;
        }
        .feature-icons {
            margin-bottom: 20px;
        }
        .icon-group {
            font-size: 2rem;
        }
        .health-features {
            text-align: left;
            padding: 10px;
        }
        .health-features p {
            font-size: 0.9rem;
            color: #666;
        }
        .health-features i {
            font-size: 1rem;
            margin-right: 8px;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .bi-heart-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        .emergency-contact {
            transition: all 0.3s ease;
        }
        .emergency-contact:hover {
            transform: scale(1.02);
        }
        .contact-form .form-control:focus,
        .contact-form .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .icon-wrapper {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include 'views/layouts/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="container">
        <div class="hero-section">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-4">SIGAP DESA</h1>
                    <p class="lead mb-5">
                        Sistem Informasi Geografis Pelaporan Desa untuk monitoring kesehatan dan bencana 
                        secara real-time dan terintegrasi.
                    </p>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="views/auth/login.php" class="btn btn-primary btn-custom me-3">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    <?php else: ?>
                        <a href="views/dashboard/index.php" class="btn btn-primary btn-custom">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row">
            <!-- Monitoring Kesehatan Card -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icons">
                        <div class="icon-group mb-3">
                            <i class="bi bi-heart-pulse text-danger"></i>
                            <i class="bi bi-clipboard2-pulse mx-2 text-primary"></i>
                            <i class="bi bi-hospital text-success"></i>
                        </div>
                    </div>
                    <h3>Monitoring Kesehatan</h3>
                    <div class="health-features">
                        <p class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Pantau kondisi kesehatan warga
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Data real-time & akurat
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Riwayat kesehatan lengkap
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h3>Pemetaan Bencana</h3>
                    <p class="text-muted">
                        Visualisasi sebaran bencana dengan peta interaktif untuk penanganan yang lebih cepat dan efektif.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Analisis Data</h3>
                    <p class="text-muted">
                        Analisis data kesehatan dan bencana untuk pengambilan keputusan yang lebih baik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="feature-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2>Tentang SIGAP DESA</h2>
                            <p class="text-muted">
                                SIGAP DESA adalah sistem informasi geografis yang dirancang khusus untuk membantu 
                                perangkat desa dalam memonitor dan mengelola data kesehatan warga serta 
                                kejadian bencana di wilayah desa. Dengan visualisasi peta yang interaktif, 
                                sistem ini memudahkan pengambilan keputusan dan penanganan yang cepat.
                            </p>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="assets/images/map-illustration.png" alt="Map Illustration" 
                                 class="img-fluid" style="max-width: 400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section dengan desain yang lebih clean dan modern -->
    <section class="contact-section py-5 bg-light" id="contact">
        <div class="container">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <p class="text-muted">Kami siap membantu 24/7 untuk keadaan darurat</p>
            </div>

            <div class="row g-4">
                <!-- Kontak Darurat Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4 text-primary">
                                <i class="bi bi-telephone-fill"></i> Kontak Darurat
                            </h4>

                            <!-- Emergency Contact -->
                            <div class="emergency-contact mb-4 p-3 bg-danger text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="emergency-icon me-3">
                                        <i class="bi bi-exclamation-circle-fill fs-1"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Nomor Darurat 24 Jam</h5>
                                        <h3 class="mb-0">112</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Contact List -->
                            <div class="quick-contacts">
                                <!-- WhatsApp -->
                                <a href="https://wa.me/6281234567890" class="text-decoration-none">
                                    <div class="contact-item d-flex align-items-center p-3 rounded mb-3 bg-light hover-shadow">
                                        <div class="icon-wrapper me-3 bg-success text-white p-2 rounded">
                                            <i class="bi bi-whatsapp fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-dark">WhatsApp Admin</h6>
                                            <small class="text-muted">+62 812-3456-7890</small>
                                        </div>
                                    </div>
                                </a>

                                <!-- Kantor Desa -->
                                <div class="contact-item d-flex align-items-center p-3 rounded mb-3 bg-light hover-shadow">
                                    <div class="icon-wrapper me-3 bg-primary text-white p-2 rounded">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Kantor Desa</h6>
                                        <small class="text-muted">Jl. Desa Wilalung Rt 05 Rw 06 Kecamatan Gajah Kabupaten Demak Jawa Tengah</small>
                                    </div>
                                </div>

                                <!-- Email -->
                                <a href="mailto:admin@sigapdesa.com" class="text-decoration-none">
                                    <div class="contact-item d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                        <div class="icon-wrapper me-3 bg-info text-white p-2 rounded">
                                            <i class="bi bi-envelope fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-dark">Email</h6>
                                            <small class="text-muted">admin@sigapdesa.com</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Hubungi Admin -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4 text-primary">
                                <i class="bi bi-chat-dots"></i> Kirim Pesan
                            </h4>

                            <?php if(isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <?= $_SESSION['success'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>

                            <form action="process_contact.php" method="POST" class="contact-form">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama" required>
                                    <label for="name">Nama Lengkap</label>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control" id="whatsapp" name="whatsapp" placeholder="WhatsApp">
                                            <label for="whatsapp">No. WhatsApp</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Pilih Subjek</option>
                                        <option value="info">Informasi Umum</option>
                                        <option value="laporan">Status Laporan</option>
                                        <option value="darurat">Keadaan Darurat</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    <label for="subject">Subjek</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="message" name="message" style="height: 100px" placeholder="Pesan" required></textarea>
                                    <label for="message">Pesan</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-send"></i> Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="mt-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-3 mb-md-0">
                                    <i class="bi bi-clock text-primary"></i> Jam Operasional
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Senin - Jumat</span>
                                    <span class="badge bg-primary">08:00 - 16:00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sabtu</span>
                                    <span class="badge bg-info">08:00 - 12:00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Minggu & Hari Libur</span>
                                    <span class="badge bg-danger">Tutup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">© <?= date('Y') ?> SIGAP DESA - Sistem Informasi Geografis Pelaporan Desa</p>
                <small class="text-muted">Dari Desa ❤️ untuk Indonesia</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
