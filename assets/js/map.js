let map;

function initMap() {
    // Inisialisasi peta dengan koordinat Desa Wilalung
    map = L.map('map').setView([-6.9123, 109.6275], 14);
    
    // Tambahkan layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Load data awal
    loadMapData();
}

function loadMapData() {
    // Load data kesehatan
    fetch('controllers/MapController.php?action=health')
        .then(response => response.json())
        .then(data => {
            data.forEach(point => {
                addHealthMarker(point);
            });
        });
    
    // Load data bencana
    fetch('controllers/MapController.php?action=disaster')
        .then(response => response.json())
        .then(data => {
            data.forEach(point => {
                addDisasterMarker(point);
            });
        });
}

function updateMapData() {
    // Update data secara real-time
    loadMapData();
}

function updateDashboardStats() {
    // Update statistik dashboard
    fetch('controllers/DashboardController.php?action=stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-citizens').textContent = data.totalCitizens;
            document.getElementById('total-health-cases').textContent = data.totalHealthCases;
            document.getElementById('total-disaster-points').textContent = data.totalDisasterPoints;
            document.getElementById('current-alert-status').textContent = data.alertStatus;
        });
} 