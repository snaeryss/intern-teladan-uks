/**
 * Pemeriksaan Umum Module - Handle perhitungan OHI-S
 * Features:
 * - Auto-calculate Debris Index (DI)
 * - Auto-calculate Calculus Index (CI)  
 * - Auto-calculate OHI-S Score dan Status
 * - Validasi input range (0-3)
 */

const PemeriksaanUmumModule = {
    init() {
        this.attachCalculationHandlers();
        this.validateInputs();
        this.triggerInitialCalculation();
    },

    /**
     * Attach event handlers untuk auto-calculation
     * Trigger saat user mengubah nilai DI atau CI
     */
    attachCalculationHandlers() {
        $(document).on('input change', '.di-input', () => {
            this.calculateDI();
        });

        $(document).on('input change', '.ci-input', () => {
            this.calculateCI();
        });
    },

    /**
     * Hitung Debris Index (DI)
     * Formula: Total semua nilai / 6
     * Range nilai: 0-3 per gigi, ada 6 titik pengukuran
     */
    calculateDI() {
        let total = 0;
        let count = 0;

        $('.di-input').each(function() {
            const value = parseFloat($(this).val()) || 0;
            if (value > 0) {
                total += value;
                count++;
            }
        });

        const average = count > 0 ? (total / 6) : 0;
        $('#skor_di').val(average.toFixed(2));

        this.calculateOHIS();
    },

    /**
     * Hitung Calculus Index (CI)
     * Formula: Total semua nilai / 6
     * Range nilai: 0-3 per gigi, ada 6 titik pengukuran
     */
    calculateCI() {
        let total = 0;
        let count = 0;

        $('.ci-input').each(function() {
            const value = parseFloat($(this).val()) || 0;
            if (value > 0) {
                total += value;
                count++;
            }
        });

        const average = count > 0 ? (total / 6) : 0;
        $('#skor_ci').val(average.toFixed(2));

        this.calculateOHIS();
    },

    /**
     * Hitung OHI-S Score dan Status
     * Formula: DI + CI = OHI-S
     * Status berdasarkan range:
     * - 0.1 - 1.2 = Baik (Good)
     * - 1.3 - 3.0 = Sedang (Fair)
     * - 3.1 - 6.0 = Buruk (Poor)
     */
    calculateOHIS() {
        const di = parseFloat($('#skor_di').val()) || 0;
        const ci = parseFloat($('#skor_ci').val()) || 0;
        const ohis = di + ci;

        $('#skor_ohis').val(ohis.toFixed(2));

        let status = '';
        if (ohis <= 1.2) {
            status = 'Baik';
        } else if (ohis <= 3.0) {
            status = 'Sedang';
        } else {
            status = 'Buruk';
        }

        $('#status_ohis').val(status);

        return { ohis, status };
    },

    /**
     * Validasi input range (0-3)
     * Jika < 0, set ke 0
     * Jika > 3, set ke 3 dan tampilkan warning
     */
    validateInputs() {
        $(document).on('blur', '.di-input, .ci-input', function() {
            let value = parseFloat($(this).val());

            if (isNaN(value)) {
                $(this).val(0);
                return;
            }

            if (value < 0) {
                $(this).val(0);
            } else if (value > 3) {
                $(this).val(3);
                Swal.fire({
                    title: 'Peringatan!',
                    html: 'Nilai maksimal untuk indeks adalah <strong>3</strong>',
                    icon: 'warning',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    },

    /**
     * Trigger perhitungan awal saat page load
     * Untuk recalculate jika ada nilai yang sudah tersimpan
     */
    triggerInitialCalculation() {
        setTimeout(() => {
            if ($('#skor_di').length) {
                this.calculateDI();
            }
            if ($('#skor_ci').length) {
                this.calculateCI();
            }
        }, 500);
    }
};

/**
 * Initialize module saat DOM ready
 * Only init jika ada input DI atau CI
 */
document.addEventListener('DOMContentLoaded', function() {
    const hasDIInputs = document.querySelector('.di-input');
    const hasCIInputs = document.querySelector('.ci-input');

    if (hasDIInputs || hasCIInputs) {
        PemeriksaanUmumModule.init();
        window.PemeriksaanUmumModule = PemeriksaanUmumModule;
    }
});