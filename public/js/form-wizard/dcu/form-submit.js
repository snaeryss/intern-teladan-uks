"use strict";

let isSaving = false;

/**
 * Submit form DCU ke database (final submit)
 * Menangani konfirmasi, validasi, dan pengiriman data lengkap
 */
async function submitFormToDatabase() {
    if (isSaving) {
        return false;
    }

    isSaving = true;

    const form = document.getElementById('dcu-form');
    if (!form) {
        Swal.fire({
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
        const locationId = document.getElementById('current_location_id')?.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            throw new Error('CSRF token tidak ditemukan');
        }

        if (!studentId || !periodId) {
            throw new Error('Student ID atau Period ID tidak ditemukan');
        }

        const confirmation = await Swal.fire({
            title: 'Konfirmasi',
            html: 'Apakah Anda yakin ingin menyelesaikan dan menyimpan pemeriksaan ini?',
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

        const payload = collectFormData(form, studentId, periodId, locationId);

        Swal.fire({
            title: 'Menyimpan Data...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch('/dcu/store', {
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
            if (typeof clearFormData === 'function') {
                clearFormData();
            }

            await Swal.fire({
                title: 'Berhasil!',
                html: `Data pemeriksaan gigi berhasil disimpan!<br><br><strong>Kode DCU:</strong> ${result.data.code}`,
                icon: 'success',
                confirmButtonText: 'OK'
            });

            window.location.href = '/record-histories';
            return true;
        } else {
            throw new Error(result.message || 'Gagal menyimpan data');
        }

    } catch (error) {
        Swal.fire({
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
 * Termasuk data matrix DI/CI dan diagnosis dari tabel
 */
function collectFormData(form, studentId, periodId, locationId) {
    const payload = {
        student_id: studentId,
        period_id: periodId,
        doctor_id: document.querySelector('meta[name="user-id"]')?.content || '{{ auth()->id() }}',
        location_id: locationId || '1',
        is_finish: true,
    };

    const userCanSave = document.querySelector('meta[name="user-can-save"]')?.content === 'true';
    if (userCanSave) {
        payload.examined_by_doctor_id = document.querySelector('meta[name="user-id"]')?.content;
    }

    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (!['student_id', 'period_id', 'doctor_id', 'location_id', 'is_finish'].includes(key)) {
            payload[key] = value;
        }
    }

    const diMatrix = extractMatrixData('di');
    if (diMatrix && diMatrix.length > 0) {
        payload.di_matrix = diMatrix;
    }

    const ciMatrix = extractMatrixData('ci');
    if (ciMatrix && ciMatrix.length > 0) {
        payload.ci_matrix = ciMatrix;
    }

    const diagnosisData = getDiagnosisTableDataForSubmit();
    if (diagnosisData.length > 0) {
        payload.diagnoses = diagnosisData;
    }

    return payload;
}

/**
 * Ekstrak data matrix DI atau CI dari input field
 * Matrix berukuran 2x3 (2 baris, 3 kolom)
 */
function extractMatrixData(type) {
    const matrix = [];

    try {
        const row1 = [];
        for (let col = 1; col <= 3; col++) {
            const elementId = `${type}_1_${col}`;
            const input = document.getElementById(elementId);
            const value = input ? parseFloat(input.value) || 0 : 0;
            row1.push(value);
        }
        matrix.push(row1);

        const row2 = [];
        for (let col = 1; col <= 3; col++) {
            const elementId = `${type}_2_${col}`;
            const input = document.getElementById(elementId);
            const value = input ? parseFloat(input.value) || 0 : 0;
            row2.push(value);
        }
        matrix.push(row2);

        return matrix;

    } catch (error) {
        // Matrix extraction failed, return null untuk skip
        return null;
    }
}

/**
 * Ambil data diagnosis dari DataTable untuk dikirim ke server
 * Format: tooth_number, diagnosis_id, notes
 */
function getDiagnosisTableDataForSubmit() {
    const diagnosisData = [];

    try {
        if (typeof $ !== 'undefined' && $('#diagnosisTable').length > 0 && $.fn.DataTable.isDataTable('#diagnosisTable')) {
            const table = $('#diagnosisTable').DataTable();
            table.rows().every(function() {
                const data = this.data();
                if (data && data[1]) {
                    const rowNode = this.node();
                    const diagnosisId = $(rowNode).find('.delete-diagnosis').data('diagnosis-id');
                    
                    diagnosisData.push({
                        tooth_number: data[1],
                        diagnosis_id: diagnosisId,
                        notes: data[3] === '-' ? null : data[3]
                    });
                }
            });
        }
    } catch (error) {
        // Diagnosis table read failed, return empty array
    }

    return diagnosisData;
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