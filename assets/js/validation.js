class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.initializeValidation();
    }

    initializeValidation() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
            }
        });
    }

    validateForm() {
        let isValid = true;
        
        // Validasi NIK
        const nik = this.form.querySelector('[name="nik"]');
        if (nik && !/^\d{16}$/.test(nik.value)) {
            this.showError(nik, 'NIK harus 16 digit angka');
            isValid = false;
        }

        // Validasi Koordinat
        const lat = this.form.querySelector('[name="latitude"]');
        const lng = this.form.querySelector('[name="longitude"]');
        if (lat && lng) {
            if (!this.isValidCoordinate(lat.value, lng.value)) {
                this.showError(lat, 'Koordinat tidak valid');
                isValid = false;
            }
        }

        return isValid;
    }

    isValidCoordinate(lat, lng) {
        const latNum = parseFloat(lat);
        const lngNum = parseFloat(lng);
        return !isNaN(latNum) && !isNaN(lngNum) && 
               latNum >= -90 && latNum <= 90 && 
               lngNum >= -180 && lngNum <= 180;
    }

    showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        element.parentNode.appendChild(errorDiv);
    }
} 