"use strict";

let isSaving = false;

/**
 * Submit form Screening ke database (final submit)
 * Menangani konfirmasi, validasi, dan pengiriman data lengkap
 */
async function submitFormToDatabase() {
    if (isSaving) {
        return false;
    }

    isSaving = true;

    const form = document.getElementById('screening-form');
    if (!form) {
        await Swal.fire({
            title: 'Error!',
            text: 'Form tidak ditemukan',
            icon: 'error'
        });
        isSaving = false;
        return false;
    }

    try {
        const studentId = document.getElementById('current_student_id')?.value;
        const periodId = document.getElementById('current_period_id')?.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            throw new Error('CSRF token tidak ditemukan');
        }

        if (!studentId || !periodId) {
            throw new Error('Student ID atau Period ID tidak ditemukan');
        }

        // Confirmation
        const confirmation = await Swal.fire({
            title: 'Konfirmasi',
            html: 'Apakah Anda yakin ingin menyelesaikan dan menyimpan pemeriksaan screening ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        });

        if (!confirmation.isConfirmed) {
            isSaving = false;
            return false;
        }

        // Collect form data
        const payload = collectFormData(form, studentId, periodId);

        // Show loading
        Swal.fire({
            title: 'Menyimpan Data...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit to server
        const response = await fetch('/screening/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const result = await handleResponse(response);

        if (result.success) {
            // Clear localStorage
            if (typeof clearFormData === 'function') {
                clearFormData();
            }

            // Success message
            await Swal.fire({
                title: 'Berhasil!',
                html: `Data pemeriksaan screening berhasil disimpan!<br><br><strong>Kode Screening:</strong> ${result.data.code}`,
                icon: 'success',
                confirmButtonText: 'OK'
            });

            // Redirect
            window.location.href = '/record-histories';
            return true;
        } else {
            throw new Error(result.message || 'Gagal menyimpan data');
        }

    } catch (error) {
        Swal.close();

        await Swal.fire({
            title: 'Gagal!',
            html: `<small>${error.message}</small>`,
            icon: 'error',
            confirmButtonText: 'OK'
        });

        isSaving = false;
        return false;
    } finally {
        isSaving = false;
    }
}

/**
 * Mengumpulkan semua data form menjadi payload JSON
 */
function collectFormData(form, studentId, periodId) {
    const payload = {
        student_id: studentId,
        period_id: periodId,
        is_finish: true,
    };

    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        payload[key] = value;
    }

    return payload;
}

/**
 * Handle response dari server
 * Parse JSON dan tangani berbagai error format
 */
async function handleResponse(response) {
    const responseText = await response.text();

    let result;
    try {
        result = JSON.parse(responseText);
    } catch (parseError) {
        if (responseText.includes('<!DOCTYPE') || responseText.includes('<html')) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(responseText, 'text/html');
            const errorTitle = doc.querySelector('h1, .exception-message, .error-message')?.textContent || 'Unknown error';
            throw new Error(`Server Error: ${errorTitle.substring(0, 200)}`);
        }
        
        throw new Error('Server mengembalikan response yang tidak valid');
    }

    if (!response.ok) {
        if (response.status === 422 && result.errors) {
            const errorMessages = Object.entries(result.errors)
                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                .join('\n');
            throw new Error(`Validasi gagal:\n${errorMessages}`);
        }
        
        const errorMsg = result.message || result.error || `HTTP Error ${response.status}`;
        throw new Error(errorMsg);
    }

    return result;
}

/**
 * Populate tab Review dengan data dari form
 * Dipanggil saat user masuk ke tab terakhir
 */
function populateReviewTab() {
    // Save current form state
    if (typeof saveFormData === 'function') {
        saveFormData();
    }

    // Call external populate function if exists
    if (typeof window.populateReviewData === 'function') {
        setTimeout(function() {
            window.populateReviewData();
        }, 100);
    }
    
    // Load evaluator doctor name
    if (typeof window.loadEvaluatorDoctorNameScreening === 'function') {
        setTimeout(function() {
            window.loadEvaluatorDoctorNameScreening();
        }, 200);
    }
}

// Export function
window.populateReviewTab = populateReviewTab;