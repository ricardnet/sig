class DashboardManager {
    constructor() {
        this.charts = {};
        this.initializeCharts();
        this.startRealTimeUpdates();
    }

    // Inisialisasi semua chart
    initializeCharts() {
        this.initHealthChart();
        this.initDisasterChart();
        this.initEvacuationChart();
    }

    // Chart untuk data kesehatan
    initHealthChart() {
        const ctx = document.getElementById('healthChart').getContext('2d');
        this.charts.health = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Sehat', 'Sakit Ringan', 'Sakit Sedang', 'Sakit Berat'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        '#27AE60',
                        '#F1C40F',
                        '#E67E22',
                        '#E74C3C'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Statistik Kesehatan Warga'
                    }
                }
            }
        });
    }

    // Update data real-time
    startRealTimeUpdates() {
        setInterval(() => {
            this.fetchUpdates();
        }, 30000); // Update setiap 30 detik
    }

    // Mengambil data terbaru
    async fetchUpdates() {
        try {
            const response = await fetch('/api/dashboard-updates');
            const data = await response.json();
            
            this.updateStatistics(data.statistics);
            this.updateCharts(data.charts);
            this.updateAlerts(data.alerts);
            
        } catch (error) {
            console.error('Error fetching updates:', error);
        }
    }

    // Update statistik
    updateStatistics(data) {
        Object.keys(data).forEach(key => {
            const element = document.getElementById(`stat-${key}`);
            if (element) {
                element.textContent = data[key];
            }
        });
    }

    // Update chart
    updateCharts(data) {
        if (data.health) {
            this.charts.health.data.datasets[0].data = data.health;
            this.charts.health.update();
        }
        // Update chart lainnya
    }

    // Menampilkan alert
    updateAlerts(alerts) {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = '';
        
        alerts.forEach(alert => {
            const alertElement = document.createElement('div');
            alertElement.className = `alert alert-${alert.type}`;
            alertElement.textContent = alert.message;
            alertContainer.appendChild(alertElement);
        });
    }
}

// Inisialisasi Dashboard
document.addEventListener('DOMContentLoaded', () => {
    const dashboard = new DashboardManager();
}); 