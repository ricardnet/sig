class RealTimeUpdater {
    constructor() {
        this.intervalId = null;
        this.updateInterval = 30000; // 30 detik
    }

    startUpdates() {
        this.intervalId = setInterval(() => {
            this.fetchUpdates();
        }, this.updateInterval);
    }

    stopUpdates() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    async fetchUpdates() {
        try {
            const response = await fetch('/api/updates');
            const data = await response.json();
            
            if (data.health) this.updateHealthMarkers(data.health);
            if (data.disasters) this.updateDisasterMarkers(data.disasters);
            if (data.stats) this.updateStatistics(data.stats);
            
        } catch (error) {
            console.error('Error fetching updates:', error);
        }
    }

    updateHealthMarkers(data) {
        // Update marker kesehatan di peta
    }

    updateDisasterMarkers(data) {
        // Update marker bencana di peta
    }

    updateStatistics(data) {
        // Update statistik di dashboard
    }
} 