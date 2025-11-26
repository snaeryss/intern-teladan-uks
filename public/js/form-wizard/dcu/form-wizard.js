"use strict";
var currentTab = 0;
var savedSteps = []; 

const formId = 'dcu-form';
let formLocalStorageKey = `formData_${formId}_generic`;
let saveTimeout = null;
let isDataTableReady = false;
let isInitialLoadComplete = false; 

const DATABASE_PRIORITY_FIELDS = [
    'dcu_date',           
    'oklusi',            
    'mukosa',
    'dmf_d', 'dmf_m', 'dmf_f',
    'frekuensi_sikat',
    'waktu_sikat',
    'pasta_gigi',
    'makanan_manis',
    'di_1_1', 'di_1_2', 'di_1_3',  
    'di_2_1', 'di_2_2', 'di_2_3',
    'ci_1_1', 'ci_1_2', 'ci_1_3',  
    'ci_2_1', 'ci_2_2', 'ci_2_3',
    'skor_di', 'skor_ci',          
    'skor_ohis', 'status_ohis',
    'ohis_keterangan'
];

/**
 * Inisialisasi localStorage key berdasarkan student_id dan period_id
 * Memastikan data tersimpan per siswa per periode
 */
function initializeLocalStorageKey() {
    const studentId = document.getElementById('current_student_id')?.value;
    const periodId = document.getElementById('current_period_id')?.value;

    if (studentId && periodId) {
        formLocalStorageKey = `formData_${formId}_student_${studentId}_period_${periodId}`;
    }
}

/**
 * Hapus field dcu_date lama dari localStorage
 * Diperlukan karena perubahan logika tanggal
 */
function clearOldDcuDateFromLocalStorage() {
    const savedData = localStorage.getItem(formLocalStorageKey);
    if (savedData) {
        try {
            const formObject = JSON.parse(savedData);
            if (formObject.hasOwnProperty('dcu_date')) {
                delete formObject.dcu_date;
                localStorage.setItem(formLocalStorageKey, JSON.stringify(formObject));
            }
        } catch (error) {
            // Silent fail - tidak kritikal
        }
    }
}

/**
 * Load diagnosis dari database (DISABLED)
 * Data diagnosis sekarang dimuat langsung dari blade template
 */
/**
 * Load diagnosis dari database
 * Dan set savedSteps jika form sudah finish
 */
async function loadDiagnosisFromDatabase() {
    const studentId = document.getElementById('current_student_id')?.value;
    const periodId = document.getElementById('current_period_id')?.value;

    if (!studentId || !periodId) {
        isInitialLoadComplete = true;
        return [];
    }

    try {
        const url = `/dcu/form/${studentId}/${periodId}/get-data`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            isInitialLoadComplete = true;
            return [];
        }

        const result = await response.json();

        if (!result.success || !result.has_data) {
            isInitialLoadComplete = true;
            return [];
        }

        const data = result.data;
        const diagnosisData = result.diagnoses || [];

        // ✅ TAMBAHAN BARU - Set savedSteps jika form sudah finish
        if (data.is_finish === 1 || data.is_finish === true || data.is_finish === "1") {
            // Form sudah selesai = SEMUA step saved
            const totalSteps = document.getElementsByClassName("tab").length;
            savedSteps = [];
            for (let i = 0; i < totalSteps; i++) {
                savedSteps.push(i);
            }
            
            // Set current tab ke step terakhir
            currentTab = totalSteps - 1;
            
            console.log('✅ DCU Form sudah finish - All steps saved:', savedSteps);
        } else {
            // Form belum selesai - set saved steps dari data yang ada
            savedSteps = [];
            
            // Step 0: Ondotogram (cek apakah ada diagnosis)
            if (diagnosisData.length > 0) {
                savedSteps.push(0);
            }
            
            // Step 1: Pemeriksaan Umum (cek oklusi, mukosa)
            if (data.oklusi || data.mukosa) {
                savedSteps.push(1);
            }
            
            // Step 2: Lainnya (evaluasi)
            if (data.catatan_dokter || data.diagnosis_dokter) {
                savedSteps.push(2);
            }
            
            // Set current tab ke step pertama yang belum saved
            const totalSteps = document.getElementsByClassName("tab").length;
            let firstUnsaved = 0;
            for (let i = 0; i < totalSteps; i++) {
                if (!savedSteps.includes(i)) {
                    firstUnsaved = i;
                    break;
                }
            }
            currentTab = firstUnsaved;
            
            console.log('📝 DCU Form belum finish - Saved steps:', savedSteps, '| Current tab:', currentTab);
        }

        isInitialLoadComplete = true;
        return diagnosisData;

    } catch (error) {
        console.error('Load diagnosis error:', error);
        isInitialLoadComplete = true;
        return [];
    }
}

/**
 * Simpan semua data form ke localStorage
 * Termasuk currentTab dan savedSteps untuk melanjutkan progress
 */
function saveFormData() {
    const form = document.getElementById(formId);
    if (!form) {
        return;
    }

    try {
        const formObject = {};
        const formData = new FormData(form);

        for (let [key, value] of formData.entries()) {
            formObject[key] = value;
        }

        formObject._currentTab = currentTab;
        formObject._savedSteps = savedSteps;
        formObject._lastSaved = new Date().toISOString();

        localStorage.setItem(formLocalStorageKey, JSON.stringify(formObject));
    } catch (error) {
        // LocalStorage save failed - tidak perlu alert karena tidak kritikal
    }
}

/**
 * Debounce save - delay 300ms sebelum save
 * Mencegah terlalu banyak operasi save saat user mengetik
 */
function debouncedSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        saveFormData();
    }, 300);
}

/**
 * Ambil data diagnosis dari DataTable (untuk display)
 * Mengembalikan array object dengan tooth number, diagnosis, notes
 */
function getDiagnosisTableData() {
    const diagnosisData = [];

    try {
        if (typeof $ !== 'undefined' && $('#diagnosisTable').length > 0) {
            if ($.fn.DataTable.isDataTable('#diagnosisTable')) {
                const table = $('#diagnosisTable').DataTable();
                
                table.rows().every(function() {
                    const data = this.data();
                    if (data && data[1]) {
                        const rowNode = this.node();
                        const diagnosisId = $(rowNode).find('.delete-diagnosis').data('diagnosis-id');
                        
                        diagnosisData.push({
                            toothNumber: data[1],
                            diagnosisId: diagnosisId,
                            diagnosisText: data[2],
                            notes: data[3] || '-'
                        });
                    }
                });
            }
        }
    } catch (error) {
        // DataTable read failed - return empty array
    }

    return diagnosisData;
}

/**
 * Load data form dari localStorage
 * Database priority: jika field sudah ada nilai dari DB, skip load dari localStorage
 */
function loadFormData() {
    const savedData = localStorage.getItem(formLocalStorageKey);
    if (!savedData) {
        return;
    }

    try {
        const formObject = JSON.parse(savedData);
        const form = document.getElementById(formId);
        if (!form) {
            return;
        }

        for (const key in formObject) {
            if (key.startsWith('_')) continue;
            
            // Cek apakah field ini priority dari database
            if (DATABASE_PRIORITY_FIELDS.includes(key)) {
                const elements = form.elements[key];
                if (!elements) continue;
                
                let hasDbValue = false;
                
                if (elements.length > 1) {
                    hasDbValue = Array.from(elements).some(el => el.checked);
                } else {
                    const element = elements;
                    if (element) {
                        if (element.hasAttribute('readonly')) {
                            continue;
                        }
                        
                        const currentValue = element.value;
                        const isMatrixField = key.match(/^(di|ci)_[12]_[123]$/);
                        
                        if (isMatrixField) {
                            hasDbValue = parseFloat(currentValue) > 0;
                        } else {
                            hasDbValue = currentValue !== '' && currentValue !== '0' && currentValue !== '0.00';
                        }
                    }
                }
                
                if (hasDbValue) {
                    continue;
                }
            }

            // Load dari localStorage jika tidak ada nilai DB
            const elements = form.elements[key];
            if (!elements) continue;

            if (elements.length > 1) {
                Array.from(elements).forEach(el => {
                    if (el.type === 'radio' || el.type === 'checkbox') {
                        if (el.value === formObject[key]) {
                            el.checked = true;
                        }
                    }
                });
            } else {
                const element = elements;
                if (element && !element.hasAttribute('readonly')) {
                    if (element.type === 'radio' || element.type === 'checkbox') {
                        element.checked = (element.value === formObject[key]);
                    } else {
                        element.value = formObject[key];
                    }
                }
            }
        }

        // Restore tab dan saved steps
        if (formObject._currentTab !== undefined) {
            currentTab = parseInt(formObject._currentTab);
        }

        if (formObject._savedSteps !== undefined) {
            savedSteps = formObject._savedSteps;
        }

        // Trigger perhitungan OHIS setelah load
        setTimeout(() => {
            triggerOHISCalculations();
        }, 500);

    } catch (error) {
        // Load form data failed - tidak perlu alert, form akan kosong
    }
}

/**
 * Tunggu DataTable ready, lalu load diagnosis
 * Retry maksimal 20x dengan interval 300ms
 */
function waitForDataTableAndLoad(diagnosisData, retries = 0) {
    const maxRetries = 20;

    if (retries >= maxRetries) {
        return;
    }

    if (typeof $ !== 'undefined' &&
        $('#diagnosisTable').length > 0 &&
        $.fn.DataTable.isDataTable('#diagnosisTable')) {

        loadDiagnosisTableData(diagnosisData);
        isDataTableReady = true;
    } else {
        setTimeout(() => {
            waitForDataTableAndLoad(diagnosisData, retries + 1);
        }, 300);
    }
}

/**
 * Load data diagnosis ke DataTable
 * Blocked jika sudah dimuat oleh ondotogram.js
 */
function loadDiagnosisTableData(diagnosisData) {
    if (window.diagnosisDataLoaded) {
        return;
    }
    
    try {
        const table = $('#diagnosisTable').DataTable();
        
        table.clear();

        diagnosisData.forEach(function(data) {
            const rowData = [
                '',
                data.toothNumber,
                data.diagnosisText,
                data.notes || '-',
                '<button type="button" class="btn btn-danger btn-sm delete-diagnosis" data-diagnosis-id="' + data.diagnosisId + '" data-tooth-number="' + data.toothNumber + '" title="Hapus">' +
                '<i class="fa fa-trash"></i></button>'
            ];

            table.row.add(rowData);
        });

        table.draw(false);
        window.diagnosisDataLoaded = true;
    } catch (error) {
        // DataTable load failed - silent fail
    }
}

/**
 * Trigger perhitungan OHIS (DI, CI, OHI-S)
 * Memanggil module PemeriksaanUmum atau trigger jQuery
 */
function triggerOHISCalculations() {
    if (typeof window.PemeriksaanUmumModule !== 'undefined') {
        window.PemeriksaanUmumModule.calculateDI();
        window.PemeriksaanUmumModule.calculateCI();
        window.PemeriksaanUmumModule.calculateOHIS();
    } else if (typeof $ !== 'undefined') {
        if ($('.di-input').length > 0) {
            $('.di-input').first().trigger('input');
        }
        if ($('.ci-input').length > 0) {
            $('.ci-input').first().trigger('input');
        }
    }
}

/**
 * Hapus data form dari localStorage
 * Dipanggil setelah submit berhasil
 */
function clearFormData() {
    localStorage.removeItem(formLocalStorageKey);
}

/**
 * Setup event listeners untuk auto-save
 * Listen ke event change dan input pada form
 */
function setupFormEventListeners() {
    document.addEventListener('change', function(e) {
        if (e.target.closest(`#${formId}`)) {
            saveFormData();
        }
    }, true);

    document.addEventListener('input', function(e) {
        if (e.target.closest(`#${formId}`)) {
            debouncedSave();
        }
    }, true);
}

/**
 * Setup listeners untuk DataTable diagnosis
 * Placeholder untuk event handlers tambahan
 */
function setupDiagnosisTableListeners() {
    if (typeof $ === 'undefined') {
        setTimeout(setupDiagnosisTableListeners, 500);
        return;
    }
}

/**
 * Populate tab Review dengan data dari form
 * Dipanggil saat masuk ke tab terakhir
 */
function populateReviewTab() {
    saveFormData();

    if (typeof window.populateReviewData === 'function') {
        setTimeout(function() {
            window.populateReviewData();
        }, 100);
    }
    if (typeof window.loadEvaluatorDoctorNameDCU === 'function') {
        setTimeout(function() {
            window.loadEvaluatorDoctorNameDCU();
        }, 200);
    }
}

/**
 * Simpan progress step saat ini ke database
 * Auto-save setiap kali next button diklik
 */
async function saveStepToDatabase() {
    const form = document.getElementById(formId);
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
        return false;
    }
    
    const studentId = document.getElementById('current_student_id')?.value;
    const periodId = document.getElementById('current_period_id')?.value;
    const csrfToken = document.querySelector('input[name="_token"]')?.value;
    const locationId = document.getElementById('current_location_id')?.value;

    if (!studentId || !periodId || !csrfToken || !locationId) {
        Swal.fire('Error', 'Data tidak lengkap.', 'error');
        return false;
    }

    const saveUrl = `/dcu/form/${studentId}/${periodId}/save-step`;
    const formData = new FormData(form);

    formData.append('location_id', locationId);
    
    const doctorId = document.querySelector('meta[name="user-id"]')?.content;
    if (doctorId) {
        formData.append('doctor_id', doctorId);
    }

    const userCanSave = document.querySelector('meta[name="user-can-save"]')?.content === 'true';
    if (userCanSave && doctorId) {
        formData.append('examined_by_doctor_id', doctorId);
    }

    let dcuDate = formData.get('dcu_date');
    if (!dcuDate || dcuDate === '' || dcuDate === 'null') {
        const today = new Date().toISOString().split('T')[0];
        formData.set('dcu_date', today);
    }

    const diagnosisData = getDiagnosisTableData();
    
    if (diagnosisData.length > 0) {
        diagnosisData.forEach((diagnosis, index) => {
            formData.append(`diagnoses[${index}][tooth_number]`, diagnosis.toothNumber);
            formData.append(`diagnoses[${index}][diagnosis_id]`, diagnosis.diagnosisId);
            formData.append(`diagnoses[${index}][notes]`, diagnosis.notes);
        });
    }

    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const response = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            Swal.close();
            
            // Tandai step ini sebagai "saved"
            if (!savedSteps.includes(currentTab)) {
                savedSteps.push(currentTab);
                saveFormData();
            }
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Progres disimpan'
            });

            return true;
        } else {
            Swal.fire('Error', `Gagal: ${result.message}`, 'error');
            return false;
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan',
            text: 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
            confirmButtonText: 'OK'
        });
        return false;
    }
}

/**
 * Tampilkan tab tertentu
 * Handle visibility tab dan tombol navigasi
 */
function showTab(n) {
    var x = document.getElementsByClassName("tab");
    if (!x[n]) {
        return;
    }

    saveFormData();

    // Populate review tab jika masuk ke tab terakhir
    if (n === (x.length - 1)) {
        populateReviewTab();
    }

    // Hide semua tab, show yang dipilih
    for (var i = 0; i < x.length; i++) {
        x[i].classList.add("d-none");
        x[i].classList.remove("active");
    }

    x[n].classList.remove("d-none");
    x[n].classList.add("active");

    // Handle tombol Previous
    var prevBtn = document.getElementById("prevBtn");
    if (prevBtn) {
        prevBtn.style.display = (n === 0) ? "none" : "inline";
    }

    // Handle tombol Next/Submit
    const canSave = document.querySelector('meta[name="user-can-save"]')?.content === 'true';
    var nextBtn = document.getElementById("nextBtn");
    var nextBtnText = document.getElementById("nextBtnText");
    
    if (n === x.length - 1) {
        if (canSave) {
            if (nextBtn) nextBtn.style.display = "inline";
            if (nextBtnText) nextBtnText.innerHTML = "Selesai & Simpan";
        } else {
            if (nextBtn) nextBtn.style.display = "none";
        }
    } else {
        if (nextBtn) nextBtn.style.display = "inline";
        if (nextBtnText) nextBtnText.innerHTML = "Selanjutnya & Simpan";
    }

    fixStepIndicator(n);
}

/**
 * Navigasi ke tab berikutnya/sebelumnya
 * Validasi form dan auto-save sebelum pindah tab
 */
async function nextPrev(n) {
    var x = document.getElementsByClassName("tab");

    if (n == 1 && !validateForm()) {
        return false;
    }

    // Jika di tab terakhir dan klik next = submit final
    if (n === 1 && currentTab === x.length - 1) {
        if (typeof submitFormToDatabase === 'function') {
            submitFormToDatabase();
        } else {
            Swal.fire('Error!', 'Fungsi submit tidak ditemukan.', 'error');
        }
        return false;
    }

    // Auto-save sebelum pindah tab
    if (n === 1) {
        const saveSuccess = await saveStepToDatabase();
        if (!saveSuccess) {
            return false;
        }
    }

    currentTab = currentTab + n;
    showTab(currentTab);
}


function fixStepIndicator(n) {
    var i, steps = document.getElementsByClassName("step");

    for (i = 0; i < steps.length; i++) {
        steps[i].classList.remove("active", "done", "saved");
    }

    for (i = 0; i < steps.length; i++) {
        if (savedSteps.includes(i)) {
            steps[i].classList.add("saved");
        }
    }

    for (i = 0; i < n; i++) {
        if(steps[i]) {
            steps[i].classList.add("done");
        }
    }

    if(steps[n]) {
        steps[n].classList.add("active");
    }
}

/**
 * Validasi form sebelum pindah tab
 * Bisa ditambahkan validasi custom di sini
 */
function validateForm() {
    return true;
}

/**
 * Inisialisasi saat DOM ready
 * Setup localStorage, load data, dan tampilkan tab pertama
 */
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(async () => {
        initializeLocalStorageKey();
        clearOldDcuDateFromLocalStorage();
        setupFormEventListeners();
        setupDiagnosisTableListeners();
        
        await loadDiagnosisFromDatabase();
        loadFormData();

        if (document.getElementsByClassName("tab").length > 0) {
            showTab(currentTab);
        }
    }, 800);
});

/**
 * Auto-save sebelum user menutup/refresh halaman
 */
window.addEventListener('beforeunload', function(e) {
    saveFormData();
});

/**
 * Callback saat DataTable siap
 * Dipanggil dari external script
 */
window.onDataTableReady = function() {
    isDataTableReady = true;
};