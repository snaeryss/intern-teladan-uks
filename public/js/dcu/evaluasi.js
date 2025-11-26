const EvaluasiModule = {
    init() {
    },

    //Memanggil semua data ke tab review
    populateReviewData() {
        const form = document.getElementById('dcu-form');
        if (!form) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Form tidak ditemukan',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        this.populateFormFields(form);
        this.populateDiagnosisTable();
        this.populateOHISData();
        this.populateDoctorName();
    },

    // Menampilkan nilai input/radio/checkbox ke elemen review
    populateFormFields(form) {
        const formData = new FormData(form);

        for (const [name, value] of formData.entries()) {
            const reviewElement = document.getElementById(`review-${name}`);

            if (reviewElement) {
                let displayValue = value.trim() === '' ? '-' : value;

                // Untuk radio button
                const radioInput = document.querySelector(`input[name="${name}"]:checked`);
                if (radioInput && radioInput.type === 'radio') {
                    const label = radioInput.nextElementSibling;
                    if (label && label.tagName === 'LABEL') {
                        displayValue = label.textContent.trim();
                    }
                }

                reviewElement.textContent = displayValue;
            }
        }
    },

    // Menampilkan tabel diagnosis gigi yang sudah diinput
    populateDiagnosisTable() {
        if (!window.diagnosisTable) {
            $('#review-diagnosis-summary').html(
                '<p class="text-muted mb-0">Data diagnosis tidak tersedia</p>'
            );
            return;
        }

        const diagnosisData = window.diagnosisTable.rows().data();

        if (diagnosisData.length === 0) {
            $('#review-diagnosis-summary').html(
                '<p class="text-muted mb-0">Belum ada diagnosis gigi</p>'
            );
            return;
        }

        let html = '<div class="table-responsive">';
        html += '<table class="table table-sm table-bordered mb-0">';
        html += '<thead class="table-light">';
        html += '<tr><th width="15%">No. Gigi</th><th>Diagnosis</th><th>Keterangan</th></tr>';
        html += '</thead><tbody>';

        diagnosisData.each((row) => {
            html += `<tr>
            <td class="fw-bold text-center">${row[1]}</td>
            <td>${row[2]}</td>
            <td>${row[3]}</td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        html += `<small class="text-muted mt-2 d-block">Total: <strong>${diagnosisData.length} diagnosis</strong></small>`;

        $('#review-diagnosis-summary').html(html);
    },

    // Menampilkan skor DI, CI, OHI-S dan status dengan badge warna
    populateOHISData() {
        const skorDI = $('#skor_di').val() || '0.00';
        const skorCI = $('#skor_ci').val() || '0.00';
        const skorOHIS = $('#skor_ohis').val() || '0.00';
        const statusOHIS = $('#status_ohis').val() || '-';

        $('#review-skor_di').text(skorDI);
        $('#review-skor_ci').text(skorCI);

        const { badgeClass, iconClass, valueTextClass } = this.getStatusStyle(statusOHIS);

        const ohisHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span><strong>Skor OHI-S:</strong></span>
                <span class="fs-4 fw-bold text-${valueTextClass}">${skorOHIS}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span><strong>Status:</strong></span>
                <span class="badge bg-${badgeClass}">
                    <i class="fa ${iconClass} me-1"></i>${statusOHIS}
                </span>
            </div>
        `;

        $('#review-ohis-summary').html(ohisHTML);
    },


    populateDoctorName() {

    },

    
    // Get badge style berdasarkan status OHI-S
    getStatusStyle(status) {
        const styles = {
            'Baik': {
                badgeClass: 'success',
                iconClass: 'fa-check-circle',
                valueTextClass: 'success'
            },
            'Sedang': {
                badgeClass: 'warning',
                iconClass: 'fa-exclamation-circle',
                valueTextClass: 'warning'
            },
            'Buruk': {
                badgeClass: 'danger',
                iconClass: 'fa-times-circle',
                valueTextClass: 'danger'
            }
        };

        return styles[status] || {
            badgeClass: 'secondary',
            iconClass: 'fa-question-circle',
            valueTextClass: 'body'
        };
    }
};


document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('review-diagnosis-summary')) {
        EvaluasiModule.init();
        window.populateReviewData = () => EvaluasiModule.populateReviewData();
        window.EvaluasiModule = EvaluasiModule;
    }
});