(function() {
    'use strict';

    /**
     * Load evaluator doctor name untuk MCU/Screening
     * Menampilkan nama dokter yang melakukan pemeriksaan di tab Review
     * - Hijau bold: dokter adalah user saat ini
     * - Orange: belum ada dokter
     * - Hijau: dokter lain
     */
    async function loadEvaluatorDoctorNameScreening() {
        const studentId = document.getElementById('current_student_id')?.value;
        const periodId = document.getElementById('current_period_id')?.value;
        
        if (!studentId || !periodId) {
            return;
        }

        const url = `/screening/form/${studentId}/${periodId}/evaluator-name`;
        
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const displayElement = document.getElementById('review-doctor-name');
                
                if (displayElement) {
                    if (data.is_current_user) {
                        displayElement.innerHTML = ': <span class="text-success fw-bold"><i class="fa fa-user-md me-1"></i>' + data.doctor_name + '</span>';
                    } else if (data.doctor_name === '-') {
                        displayElement.innerHTML = ': <span class="text-warning"><i class="fa fa-clock me-1"></i>Belum ada dokter</span>';
                    } else {
                        displayElement.innerHTML = ': <span class="text-success"><i class="fa fa-user-doctor me-1"></i>' + data.doctor_name + '</span>';
                    }
                }
            } else {
                // Failed to load - silent
            }
        } catch (error) {
        
        }
    }

    /**
     * Load evaluator doctor name untuk DCU
     * Menampilkan nama dokter gigi yang melakukan pemeriksaan di tab Review
     * - Hijau bold: dokter adalah user saat ini
     * - Orange: menunggu pemeriksaan dokter
     * - Hijau: dokter lain
     */
    async function loadEvaluatorDoctorNameDCU() {
        const studentId = document.getElementById('current_student_id')?.value;
        const periodId = document.getElementById('current_period_id')?.value;
        
        if (!studentId || !periodId) {
            return;
        }

        const url = `/dcu/form/${studentId}/${periodId}/evaluator-name`;
        
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const displayElement = document.getElementById('review-doctor-name');
                
                if (displayElement) {
                    if (data.is_current_user) {
                        displayElement.innerHTML = ': <span class="text-success fw-bold"><i class="fa fa-user-md me-1"></i>' + data.doctor_name + '</span>';
                    } else if (data.doctor_name === '-') {
                        displayElement.innerHTML = ': <span class="text-warning"><i class="fa fa-clock me-1"></i>Menunggu pemeriksaan dokter</span>';
                    } else {
                        displayElement.innerHTML = ': <span class="text-success"><i class="fa fa-user-doctor me-1"></i>' + data.doctor_name + '</span>';
                    }
                }
            } else {
                // Failed to load - silent
            }
        } catch (error) {
        }
    }

    /**
     * Register functions ke global scope
     * Agar bisa dipanggil dari form-wizard modules
     */
    window.loadEvaluatorDoctorNameScreening = loadEvaluatorDoctorNameScreening;
    window.loadEvaluatorDoctorNameDCU = loadEvaluatorDoctorNameDCU;

})();