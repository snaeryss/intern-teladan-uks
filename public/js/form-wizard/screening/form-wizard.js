"use strict";

// ============================================
// GLOBAL VARIABLES
// ============================================
var currentTab = 0;
var savedSteps = []; // Track which steps have been saved to DB

const formId = 'screening-form';
let formLocalStorageKey = `formData_${formId}_generic`;
let saveTimeout = null;

// ============================================
// DATABASE PRIORITY FIELDS
// Fields yang HARUS dari database, jangan di-overwrite localStorage
// ============================================
const DATABASE_PRIORITY_FIELDS = [
    'berat_badan',
    'tinggi_badan',
    'imt',
    'status_gizi',
    'lingkar_kepala',
    'lingkar_lengan_atas',
    'lingkar_perut',
    'bb_u',
    'tb_u',
    'anemia',
    'anemi',
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
 * Hapus field lama dari localStorage yang seharusnya dari database
 * Termasuk tanggal_periksa yang harus selalu dari blade
 */
function clearOldDataFromLocalStorage() {
    const savedData = localStorage.getItem(formLocalStorageKey);
    if (savedData) {
        try {
            const formObject = JSON.parse(savedData);
            let needsUpdate = false;
            
            // CRITICAL: Always delete tanggal_periksa from localStorage
            if (formObject.hasOwnProperty('tanggal_periksa')) {
                delete formObject.tanggal_periksa;
                needsUpdate = true;
            }
            
            // Clear fields yang seharusnya dari database
            DATABASE_PRIORITY_FIELDS.forEach(field => {
                if (formObject.hasOwnProperty(field)) {
                    delete formObject[field];
                    needsUpdate = true;
                }
            });
            
            if (needsUpdate) {
                localStorage.setItem(formLocalStorageKey, JSON.stringify(formObject));
            }
        } catch (error) {
            // Clear old data failed - silent
        }
    }
}

/**
 * Simpan semua data form ke localStorage
 * EXCLUDE tanggal_periksa (harus selalu dari blade)
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
            // NEVER save tanggal_periksa to localStorage
            if (key === 'tanggal_periksa') {
                continue;
            }
            formObject[key] = value;
        }

        formObject._currentTab = currentTab;
        formObject._savedSteps = savedSteps;
        formObject._lastSaved = new Date().toISOString();

        localStorage.setItem(formLocalStorageKey, JSON.stringify(formObject));
    } catch (error) {
        // Save failed - silent
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
 * Load data dari database (priority tertinggi)
 * Jika ada data di database, gunakan itu
 */
async function loadFromDatabase() {
    const studentId = document.getElementById('current_student_id')?.value;
    const periodId = document.getElementById('current_period_id')?.value;

    if (!studentId || !periodId) {
        return false;
    }

    try {
        const url = `/screening/form/${studentId}/${periodId}/get-data`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            return false;
        }

        const result = await response.json();

        if (!result.success || !result.has_data) {
            return false;
        }

        // Populate form dengan data dari DB
        const form = document.getElementById(formId);
        if (!form) return false;

        const data = result.data;

        for (const key in data) {
            const elements = form.elements[key];
            if (!elements) continue;

            let value = data[key];
            if (value === null || value === undefined) continue;

            // Handle radio buttons
            if (elements.length > 1) {
                Array.from(elements).forEach(el => {
                    if (el.type === 'radio' && el.value === value) {
                        el.checked = true;
                    }
                });
            } else {
                const element = elements;
                if (element) {
                    // Convert date format
                    if (element.type === 'date' && value) {
                        value = value.split('T')[0];
                    }
                    
                    element.value = value;
                }
            }
        }
        
        // Store mcu_id and doctor name for later use
        if (data.mcu_id) {
            window.existingMcuId = data.mcu_id;
        }
        
        if (data.doctor_name) {
            window.doctorName = data.doctor_name;
        }

        // ✅ TAMBAHAN BARU - Set savedSteps dan currentTab
        // Cek apakah MCU sudah finish
        if (data.is_finish === 1 || data.is_finish === true || data.is_finish === "1") {
            // Form sudah selesai = SEMUA step saved
            const totalSteps = document.getElementsByClassName("tab").length;
            savedSteps = [];
            for (let i = 0; i < totalSteps; i++) {
                savedSteps.push(i);
            }
            
            // Set current tab ke step terakhir (evaluasi)
            currentTab = totalSteps - 1;
            
            console.log('✅ Form sudah finish - All steps saved:', savedSteps);
        } else {
            // Form belum selesai - set saved steps dari data yang ada
            // Logika sederhana: cek field penting per step
            savedSteps = [];
            
            // Step 0: Status Gizi (cek berat_badan, tinggi_badan)
            if (data.berat_badan && data.tinggi_badan) {
                savedSteps.push(0);
            }
            
            // Step 1: Pemeriksaan Umum / Tanda Vital (cek tekanan_darah)
            if (data.tekanan_darah_sistole || data.suhu_tubuh) {
                savedSteps.push(1);
            }
            
            // Step 2: Kebersihan Diri / Gigi (cek field tertentu)
            if (data.gigi_berlubang !== null && data.gigi_berlubang !== undefined) {
                savedSteps.push(2);
            }
            
            // Step 3, 4, 5... (bisa ditambahkan logic serupa)
            // Untuk sementara, anggap step 3-5 saved jika ada data tertentu
            if (data.mata_kanan_normal !== null) savedSteps.push(3);
            if (data.riwayat_penyakit) savedSteps.push(4);
            if (data.diagnosis_dokter) savedSteps.push(5);
            
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
            
            console.log('📝 Form belum finish - Saved steps:', savedSteps, '| Current tab:', currentTab);
        }

        // Trigger calculations after loading data
        setTimeout(() => {
            triggerCalculations();
        }, 500);

        return true;

    } catch (error) {
        console.error('Load from database error:', error);
        return false;
    }
}

/**
 * Load data dari localStorage (fallback jika tidak ada di database)
 * Database priority: jika field sudah ada nilai dari DB, skip load dari localStorage
 */
function loadFromLocalStorage() {
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
            
            // CRITICAL: NEVER override tanggal_periksa from blade
            if (key === 'tanggal_periksa') {
                continue;
            }
            
            // DATABASE PRIORITY LOGIC
            if (DATABASE_PRIORITY_FIELDS.includes(key)) {
                const elements = form.elements[key];
                if (!elements) continue;
                
                let hasDbValue = false;

                if (elements.length > 1) {
                    hasDbValue = Array.from(elements).some(el => el.checked);
                } else {
                    const element = elements;
                    if (element && !element.hasAttribute('readonly')) {
                        const currentValue = element.value;
                        hasDbValue = currentValue !== '' && currentValue !== '0' && currentValue !== '0.00';
                    }
                }

                if (hasDbValue) {
                    continue;
                }
            }

            // Load from localStorage
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
                    // Skip tanggal_periksa
                    if (key === 'tanggal_periksa') {
                        continue;
                    }
                    
                    if (element.type === 'radio' || element.type === 'checkbox') {
                        element.checked = (element.value === formObject[key]);
                    } else {
                        element.value = formObject[key];
                    }
                }
            }
        }

        // Restore tab position
        if (formObject._currentTab !== undefined) {
            currentTab = parseInt(formObject._currentTab);
        }

        // Restore saved steps
        if (formObject._savedSteps !== undefined) {
            savedSteps = formObject._savedSteps;
        }

        // Trigger calculations
        setTimeout(() => {
            triggerCalculations();
        }, 500);

    } catch (error) {
        // Load failed - silent
    }
}

/**
 * Trigger perhitungan IMT dan umur dari tanggal lahir
 * Dipanggil setelah load data
 */
function triggerCalculations() {
    if (typeof $ !== 'undefined') {
        const beratBadan = $('#berat_badan');
        const tinggiBadan = $('#tinggi_badan');
        
        if (beratBadan.length > 0 && tinggiBadan.length > 0) {
            if (beratBadan.val() && tinggiBadan.val()) {
                beratBadan.trigger('input');
            }
        }
        
        const tanggalPeriksa = $('#tanggal_periksa');
        if (tanggalPeriksa.length > 0 && tanggalPeriksa.val()) {
            tanggalPeriksa.trigger('change');
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
 * Load form data: prioritas DATABASE dulu, lalu localStorage
 */
async function loadFormData() {
    // STEP 1: Load dari DATABASE dulu
    const hasDbData = await loadFromDatabase();
    
    if (hasDbData) {
        return;
    }

    // STEP 2: Jika tidak ada di DB, load dari localStorage
    loadFromLocalStorage();
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

    if (!studentId || !periodId || !csrfToken) {
        await Swal.fire('Error', 'Data tidak lengkap.', 'error');
        return false;
    }

    const saveUrl = `/screening/form/${studentId}/${periodId}/save-step`;
    const formData = new FormData(form);

    // Tambah step number
    formData.append('step_number', currentTab);

    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu...',
        allowOutsideClick: false,
        allowEscapeKey: false,
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

        Swal.close();

        if (response.ok && result.success) {
            // Tandai step ini sebagai saved
            if (!savedSteps.includes(currentTab)) {
                savedSteps.push(currentTab);
            }
            
            // Show toast notification
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            
            await Toast.fire({
                icon: 'success',
                title: 'Progres disimpan'
            });

            // Auto-save to localStorage
            saveFormData();

            return true;
        } else {
            await Swal.fire('Error', `Gagal: ${result.message}`, 'error');
            return false;
        }
    } catch (error) {
        Swal.close();
        
        await Swal.fire({
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
    if (!x[n]) return;

    // Save to localStorage whenever tab changes
    saveFormData();

    // Hide all tabs
    for (var i = 0; i < x.length; i++) {
        x[i].classList.add("d-none");
        x[i].classList.remove("active");
    }

    // Show current tab
    x[n].classList.remove("d-none");
    x[n].classList.add("active");

    // Get user permissions
    const canSave = document.querySelector('meta[name="user-can-save"]')?.content === 'true';
    const isPerawatUks = document.querySelector('meta[name="is-perawat-uks"]')?.content === 'true';
    
    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");
    var nextBtnText = document.getElementById("nextBtnText");
    
    // ============================================
    // BUTTON LOGIC UNTUK PERAWAT UKS
    // ============================================
    if (isPerawatUks) {
        // Perawat UKS: Hanya tombol "Simpan"
        if (prevBtn) prevBtn.style.display = "none";
        if (nextBtn) {
            nextBtn.style.display = "inline";
            nextBtn.classList.remove('btn-primary');
            nextBtn.classList.add('btn-success');
        }
        if (nextBtnText) {
            nextBtnText.innerHTML = "Simpan";
        }
        
    } else {
        // ============================================
        // BUTTON LOGIC UNTUK ROLE LAIN
        // ============================================
        
        // Tombol "Sebelumnya"
        if (prevBtn) {
            prevBtn.style.display = (n === 0) ? "none" : "inline";
        }
        
        // Tombol "Selanjutnya/Simpan"
        if (n === x.length - 1) {
            // Tab terakhir (Evaluasi)
            if (canSave) {
                if (nextBtn) {
                    nextBtn.style.display = "inline";
                    nextBtn.classList.remove('btn-primary');
                    nextBtn.classList.add('btn-success');
                }
                if (nextBtnText) {
                    nextBtnText.innerHTML = "Selesai & Simpan";
                }
            } else {
                // Non-dokter tidak bisa save
                if (nextBtn) nextBtn.style.display = "none";
            }
        } else {
            // Tab bukan terakhir
            if (nextBtn) {
                nextBtn.style.display = "inline";
                nextBtn.classList.remove('btn-success');
                nextBtn.classList.add('btn-primary');
            }
            if (nextBtnText) {
                nextBtnText.innerHTML = "Selanjutnya & Simpan";
            }
        }

        // Populate review tab jika tab terakhir
        if (n === x.length - 1 && typeof populateReviewTab === 'function') {
            populateReviewTab();
        }
    }

    // Update step indicator
    fixStepIndicator(n);
}

/**
 * Navigasi ke tab berikutnya/sebelumnya
 * Validasi form dan auto-save sebelum pindah tab
 */
async function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (x.length === 0) {
        return;
    }

    // Validate before moving forward
    if (n == 1 && !validateForm()) {
        return false;
    }

    const isPerawatUks = document.querySelector('meta[name="is-perawat-uks"]')?.content === 'true';

    try {
        // ============================================
        // PERAWAT UKS LOGIC - Save & Redirect
        // ============================================
        if (isPerawatUks && n === 1) {
            const saved = await saveStepToDatabase();
            
            if (saved) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data Status Gizi berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Redirect ke waiting list
                window.location.href = '/waiting-list';
            }
            
            return false;
        }
        
        // ============================================
        // DOKTOR/DOKTOR GIGI LOGIC
        // ============================================
        
        // FINAL SUBMIT (tab terakhir)
        if (n === 1 && currentTab === x.length - 1) {
            if (typeof submitFormToDatabase === 'function') {
                await submitFormToDatabase();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Fungsi submit tidak ditemukan.'
                });
            }
            
            return false;
        }
        
        // SAVE STEP & MOVE TO NEXT (bukan tab terakhir)
        if (n === 1) {
            const saved = await saveStepToDatabase();
            
            if (!saved) {
                return false;
            }
        }

        // ============================================
        // MOVE TO NEXT/PREV TAB
        // ============================================
        currentTab = currentTab + n;

        // Prevent going beyond bounds
        if (currentTab >= x.length) {
            currentTab = x.length - 1;
            return false;
        }

        if (currentTab < 0) {
            currentTab = 0;
            return false;
        }

        showTab(currentTab);
        
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan: ' + error.message
        });
    }
}


function fixStepIndicator(n) {
    var steps = document.querySelectorAll(".step");
    
    steps.forEach(function (step, index) {
        step.classList.remove("active", "finish", "saved");
        
        if (savedSteps.includes(index)) {
            step.classList.add("saved");
        }
 
        if (index < n) {
            step.classList.add("finish");
        }
 
        if (index === n) {
            step.classList.add("active");
        }
    });
}

/**
 * Validasi form sebelum pindah tab
 * Cek required fields dan tampilkan error jika ada yang kosong
 */
function validateForm() {
    var x = document.getElementsByClassName("tab");
    var currentFields = x[currentTab].querySelectorAll("input, select, textarea");
    var valid = true;
    var errorMessages = [];

    currentFields.forEach(function (field) {
        if (!field.hasAttribute("required")) {
            return;
        }

        const fieldValue = field.value.trim();
        const fieldName = field.getAttribute('data-label') || field.name || 'Field';
        
        if (!fieldValue) {
            field.classList.add("is-invalid");
            valid = false;
            errorMessages.push(fieldName);
            
            // Untuk select2
            if (field.classList.contains('select2-hidden-accessible')) {
                const select2Container = field.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    select2Container.querySelector('.select2-selection').classList.add('is-invalid');
                }
            }
        } else {
            field.classList.remove("is-invalid");
            
            if (field.classList.contains('select2-hidden-accessible')) {
                const select2Container = field.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    select2Container.querySelector('.select2-selection').classList.remove('is-invalid');
                }
            }
        }
    });

    if (!valid) {
        Swal.fire({
            icon: 'warning',
            title: 'Form Belum Lengkap',
            html: `Mohon lengkapi field berikut:<br><strong>${errorMessages.join(', ')}</strong>`,
            confirmButtonText: 'OK'
        });
    }

    return valid;
}

/**
 * Handle klik pada step indicator
 * Validasi akses untuk Perawat UKS, lalu navigate ke step
 */
function handleStepClick(stepElement) {
    const isPerawatUks = document.querySelector('meta[name="is-perawat-uks"]')?.content === 'true';
    const clickedStep = parseInt(stepElement.getAttribute('data-step'));
    
    // Validasi akses untuk Perawat UKS
    if (isPerawatUks) {
        const studentLevel = document.querySelector('meta[name="student-level"]')?.content;
        const allowedStep = (studentLevel === 'smp' || studentLevel === 'sma') ? 1 : 0;
        
        if (clickedStep !== allowedStep) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: 'Anda tidak memiliki akses ke step ini.',
                confirmButtonText: 'OK'
            });
            
            return false;
        }
    }
    
    // Navigate ke step
    if (!isNaN(clickedStep)) {
        currentTab = clickedStep;
        showTab(clickedStep);
    }
}

/**
 * Setup event listeners untuk auto-save
 * Listen ke event change dan input pada form
 */
function setupFormEventListeners() {
    // Auto-save on change
    document.addEventListener('change', function(e) {
        if (e.target.closest(`#${formId}`)) {
            saveFormData();
        }
    }, true);

    // Debounced save on input
    document.addEventListener('input', function(e) {
        if (e.target.closest(`#${formId}`)) {
            debouncedSave();
        }
    }, true);

    // Auto-remove invalid class on change
    document.addEventListener('change', function(e) {
        const target = e.target;
        
        if (target.classList.contains('is-invalid')) {
            target.classList.remove('is-invalid');
            
            if (target.classList.contains('select2-hidden-accessible')) {
                const select2Container = target.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    select2Container.querySelector('.select2-selection')?.classList.remove('is-invalid');
                }
            }
        }
    });
}

/**
 * Inisialisasi saat DOM ready
 * Setup localStorage, load data, dan tampilkan tab pertama
 */
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(async () => {
        // Initialize
        initializeLocalStorageKey();
        clearOldDataFromLocalStorage();
        setupFormEventListeners();
        
        // Load data (DB first, then localStorage)
        await loadFormData();
        
        // Check user role & determine starting step
        const isPerawatUks = document.querySelector('meta[name="is-perawat-uks"]')?.content === 'true';
        const studentLevel = document.querySelector('meta[name="student-level"]')?.content;
        
        if (isPerawatUks) {
            // Perawat UKS start di step Status Gizi
            currentTab = (studentLevel === 'smp' || studentLevel === 'sma') ? 1 : 0;
        } else {
            // Normal user start dari step 0
            currentTab = 0;
        }
        
        // Show initial tab
        showTab(currentTab);
        
        // ============================================
        // SETUP BUTTON EVENT LISTENERS
        // ============================================
        var prevBtn = document.getElementById("prevBtn");
        var nextBtn = document.getElementById("nextBtn");

        if (prevBtn) {
            prevBtn.addEventListener("click", function () {
                nextPrev(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", function () {
                nextPrev(1);
            });
        }
        
        // Setup step click handlers
        setTimeout(() => {
            const steppers = document.querySelectorAll('#screening-stepper .step[data-step]');
            
            steppers.forEach(function(stepper) {
                stepper.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleStepClick(this);
                });
            });
        }, 500);

    }, 500);
});

/**
 * Auto-save sebelum user menutup/refresh halaman
 */
window.addEventListener('beforeunload', function(e) {
    saveFormData();
});

/**
 * Utility functions untuk external use
 */
function getCurrentTab() {
    return currentTab;
}

function goToTab(tabNumber) {
    if (tabNumber >= 0 && tabNumber < document.getElementsByClassName("tab").length) {
        currentTab = tabNumber;
        showTab(currentTab);
        return true;
    }
    return false;
}

// Export for external use
window.formWizard = {
    getCurrentTab: getCurrentTab,
    goToTab: goToTab,
    showTab: showTab,
    nextPrev: nextPrev
};