<?php
// Dapatkan nama file saat ini untuk highlight menu aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="/sig">
            <img src="/sig/assets/images/logo.png" alt="SIGAP DESA" height="30" class="me-2">
            <span>SIGAP DESA</span>
        </a>

        <!-- Tombol Toggle untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Navbar -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Menu Utama -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" 
                           href="/sig/views/dashboard/index.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Dropdown Kesehatan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= strpos($current_page, 'health') !== false ? 'active' : '' ?>" 
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-heart-pulse"></i> Kesehatan
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="/sig/views/health/index.php">
                                    <i class="bi bi-list"></i> Data Kesehatan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/sig/views/health/create.php">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Dropdown Bencana -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= strpos($current_page, 'disaster') !== false ? 'active' : '' ?>" 
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-exclamation-triangle"></i> Bencana
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="/sig/views/disaster/index.php">
                                    <i class="bi bi-list"></i> Data Bencana
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/sig/views/disaster/create.php">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Menu Peta -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($current_page, 'maps') !== false ? 'active' : '' ?>" 
                           href="/sig/views/maps/index.php">
                            <i class="bi bi-map"></i> Peta
                        </a>
                    </li>
                </ul>

                <!-- Menu User -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/sig/views/profile/index.php">
                                    <i class="bi bi-person"></i> Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/sig/views/auth/logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- CSS tambahan -->
<style>
.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}

.navbar-brand {
    font-weight: bold;
}

.nav-link {
    padding: .5rem 1rem;
}

.dropdown-item {
    padding: .5rem 1rem;
}

.dropdown-item i {
    width: 1.2rem;
    margin-right: .5rem;
}

@media (max-width: 991.98px) {
    .navbar-nav .dropdown-menu {
        border: none;
        padding-left: 1rem;
        background: transparent;
    }
    
    .navbar-nav .dropdown-item {
        color: rgba(255,255,255,.75);
    }
    
    .navbar-nav .dropdown-item:hover {
        color: #fff;
        background: transparent;
    }
}
</style> 