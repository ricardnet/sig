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

                        <!-- Emergency Call -->
                        <div class="contact-item d-flex align-items-center p-3 mb-3 bg-light rounded">
                            <div class="icon-wrapper bg-danger text-white rounded-circle p-3 me-3">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Telepon Darurat</h6>
                                <a href="tel:112" class="text-danger text-decoration-none">112</a>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="contact-item d-flex align-items-center p-3 mb-3 bg-light rounded">
                            <div class="icon-wrapper bg-success text-white rounded-circle p-3 me-3">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">WhatsApp Desa</h6>
                                <a href="https://wa.me/6281234567890" class="text-success text-decoration-none">
                                    +62 812-3456-7890
                                </a>
                            </div>
                        </div>

                        <!-- Kantor Desa -->
                        <div class="contact-item d-flex align-items-center p-3 bg-light rounded">
                            <div class="icon-wrapper bg-primary text-white rounded-circle p-3 me-3">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Kantor Desa</h6>
                                <p class="mb-0 text-muted small">
                                    Jl. Desa No. 123, Kecamatan Example<br>
                                    Buka: Senin - Jumat (08.00 - 16.00)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Hubungi Admin -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4 text-primary">
                            <i class="bi bi-chat-dots-fill"></i> Hubungi Admin
                        </h4>

                        <form action="process_contact.php" method="POST" class="needs-validation" novalidate>
                            <!-- Nama -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Nama Lengkap" required>
                                <label for="name">Nama Lengkap</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Email" required>
                                <label for="email">Email</label>
                            </div>

                            <!-- WhatsApp -->
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="whatsapp" name="whatsapp" 
                                       placeholder="WhatsApp">
                                <label for="whatsapp">No. WhatsApp (Opsional)</label>
                            </div>

                            <!-- Subjek -->
                            <div class="form-floating mb-3">
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Pilih Subjek</option>
                                    <option value="darurat">Keadaan Darurat</option>
                                    <option value="laporan">Status Laporan</option>
                                    <option value="info">Informasi Umum</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <label for="subject">Subjek</label>
                            </div>

                            <!-- Pesan -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="message" name="message" 
                                          style="height: 100px" required></textarea>
                                <label for="message">Pesan</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>
                        Untuk keadaan darurat di luar jam kerja, silakan hubungi nomor darurat atau WhatsApp Desa.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tambahkan CSS berikut di bagian head atau file CSS terpisah -->
<style>
    .contact-section {
        background-color: #f8f9fa;
    }
    
    .icon-wrapper {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
    }
    
    .contact-item:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .contact-item {
        transition: transform 0.3s;
    }
    
    .contact-item:hover {
        transform: translateX(5px);
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }
    
    .form-floating > textarea.form-control {
        height: 100px;
    }
    
    .btn-primary {
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<!-- Tambahkan JavaScript untuk validasi form -->
<script>
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