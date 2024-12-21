<?php
// Fungsi untuk mendapatkan total warga
function getTotalCitizens() {
    global $db;
    try {
        $query = "SELECT COUNT(*) as total FROM health_records";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch(PDOException $e) {
        return 0;
    }
}

// Fungsi untuk mendapatkan total kasus kesehatan
function getTotalHealthCases() {
    global $db;
    try {
        $query = "SELECT COUNT(*) as total FROM health_records WHERE status != 'sehat'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch(PDOException $e) {
        return 0;
    }
}

// Fungsi untuk mendapatkan total titik rawan bencana
function getTotalDisasterPoints() {
    global $db;
    try {
        $query = "SELECT COUNT(*) as total FROM disaster_records WHERE status = 'aktif'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch(PDOException $e) {
        return 0;
    }
}

// Fungsi untuk mendapatkan status siaga terkini
function getCurrentAlertStatus() {
    global $db;
    try {
        $query = "SELECT severity_level FROM disaster_records 
                  WHERE status = 'aktif' 
                  ORDER BY severity_level DESC 
                  LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? strtoupper($result['severity_level']) : 'NORMAL';
    } catch(PDOException $e) {
        return 'NORMAL';
    }
}

// Fungsi untuk mendapatkan data kesehatan terbaru
function getRecentHealthData() {
    global $db;
    try {
        $query = "SELECT * FROM health_records 
                  ORDER BY created_at DESC 
                  LIMIT 5";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $html = '<div class="list-group">';
        foreach($results as $row) {
            $html .= sprintf(
                '<a href="views/health/detail.php?id=%d" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">%s</h6>
                        <small>%s</small>
                    </div>
                    <p class="mb-1">Status: %s</p>
                </a>',
                $row['id'],
                htmlspecialchars($row['citizen_name']),
                date('d/m/Y H:i', strtotime($row['created_at'])),
                htmlspecialchars($row['status'])
            );
        }
        $html .= '</div>';
        return $html;
    } catch(PDOException $e) {
        return '<div class="alert alert-danger">Gagal memuat data</div>';
    }
}

// Fungsi untuk mendapatkan peringatan bencana terkini
function getRecentDisasterAlerts() {
    global $db;
    try {
        $query = "SELECT * FROM disaster_records 
                  WHERE status = 'aktif' 
                  ORDER BY created_at DESC 
                  LIMIT 5";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $html = '<div class="list-group">';
        foreach($results as $row) {
            $alertClass = '';
            switch($row['severity_level']) {
                case 'tinggi': $alertClass = 'list-group-item-danger'; break;
                case 'sedang': $alertClass = 'list-group-item-warning'; break;
                default: $alertClass = 'list-group-item-info';
            }
            
            $html .= sprintf(
                '<a href="views/disaster/detail.php?id=%d" class="list-group-item list-group-item-action %s">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">%s</h6>
                        <small>%s</small>
                    </div>
                    <p class="mb-1">Level: %s</p>
                </a>',
                $row['id'],
                $alertClass,
                htmlspecialchars($row['disaster_type']),
                date('d/m/Y H:i', strtotime($row['created_at'])),
                strtoupper($row['severity_level'])
            );
        }
        $html .= '</div>';
        return $html;
    } catch(PDOException $e) {
        return '<div class="alert alert-danger">Gagal memuat data</div>';
    }
}

// Fungsi untuk mendapatkan halaman saat ini
function getCurrentPage() {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    return str_replace('.php', '', $file);
}
?>
