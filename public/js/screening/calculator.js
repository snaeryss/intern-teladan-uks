$(document).ready(function() {

    function calculateAge() {
        const birthDate = $('#student_birth_date').val();
        const checkDate = $('#tanggal_periksa').val() || new Date().toISOString().split('T')[0];
        
        if (!birthDate) {
            $('#umur').val('Tanggal lahir tidak tersedia');
            return null;
        }

        const birth = new Date(birthDate);
        const check = new Date(checkDate);
        
        let years = check.getFullYear() - birth.getFullYear();
        let months = check.getMonth() - birth.getMonth();
        
        if (months < 0) {
            years--;
            months += 12;
        }
        
        const totalMonths = (years * 12) + months;
        const ageText = `${years} Tahun ${months} Bulan`;
        
        $('#umur').val(ageText);
        
        return { years, months, totalMonths, text: ageText };
    }

    $('#tanggal_periksa').on('change', function() {
        calculateAge();
        calculateNutritionalStatus();
    });

    calculateAge();

    function calculateNutritionalStatus() {
        const bb = parseFloat($('#berat_badan').val());
        const tb = parseFloat($('#tinggi_badan').val());
        const studentId = $('#current_student_id').val();

        if (!bb || !tb || bb <= 0 || tb <= 0) {
            $('#imt').val('');
            $('#status_gizi').val('');
            return;
        }

        if (!studentId) {
            $('#imt').val('');
            $('#status_gizi').val('Error: Student ID tidak ditemukan');
            return;
        }

        $('#imt').val('Menghitung...');
        $('#status_gizi').val('Menghitung...');

        // Get CSRF token
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (!csrfToken) {
            csrfToken = $('input[name="_token"]').val();
        }

        if (!csrfToken) {
            $('#imt').val('Error');
            $('#status_gizi').val('CSRF token missing');
            return;
        }

        $.ajax({
            url: '/mcu/evaluate-bmi', 
            method: 'POST',
            data: {
                student_id: studentId,
                weight: bb,
                height: tb,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $('#imt').val(response.bmi);
                    $('#status_gizi').val(response.nutritional_status_label);
                } else {
                    $('#imt').val('-');
                    $('#status_gizi').val(response.message || 'Error');
                }
            },
            error: function(xhr, status, error) {
                $('#imt').val('Error');
                $('#status_gizi').val('Gagal menghitung');

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Error BMI Calculation: ' + xhr.responseJSON.message);
                } else {
                    alert('Error: Gagal menghitung BMI. Silakan coba lagi.');
                }
            }
        });
    }

    $('#berat_badan, #tinggi_badan').on('input', function() {
        clearTimeout(window.bmiCalculationTimeout);
        window.bmiCalculationTimeout = setTimeout(calculateNutritionalStatus, 500);
    });

    setTimeout(function() {
        const bb = parseFloat($('#berat_badan').val());
        const tb = parseFloat($('#tinggi_badan').val());
        
        if (bb && tb && bb > 0 && tb > 0) {
            calculateNutritionalStatus();
        }
    }, 100);

});