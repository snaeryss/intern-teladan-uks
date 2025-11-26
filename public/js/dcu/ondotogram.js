window.selectedToothId = null;
window.diagnosisTable = null;
window.diagnosisDataLoaded = false;

// Ondotogram Module - Handle interaksi gigi dan tabel diagnosis
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            
            try {
                // Initialize DataTable
                window.diagnosisTable = $('#diagnosisTable').DataTable({
                    "autoWidth": false,
                    "pageLength": 50,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "ordering": false,
                    "language": {
                        "emptyTable": "Belum ada data diagnosis"
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": "_all" }
                    ],
                    "drawCallback": function() {
                        this.api().column(0).nodes().each(function(cell, i) {
                            cell.innerHTML = (i + 1) + ".";
                        });
                    }
                });

                // Load existing diagnoses dari blade
                if (window.existingDiagnoses && window.existingDiagnoses.length > 0 && !window.diagnosisDataLoaded) {
                    window.existingDiagnoses.forEach(function(diag) {
                        window.diagnosisTable.row.add([
                            '',
                            diag.tooth_number,
                            diag.description,
                            diag.notes || "-",
                            '<button type="button" class="btn btn-danger btn-sm delete-diagnosis" data-diagnosis-id="' + diag.dental_diagnosis_id + '" data-tooth-number="' + diag.tooth_number + '" title="Hapus"><i class="fa fa-trash"></i></button>'
                        ]).draw(false);
                    });
                    window.diagnosisDataLoaded = true;
                }

                if (typeof window.onDataTableReady === 'function') {
                    window.onDataTableReady();
                }

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal menginisialisasi tabel diagnosis',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }
            

            // Klik Gambar Gigi dan menampilkan modal diagnosis dengan nomor gigi sudah terisi
            $(document).on('click', '.tooth-img', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                window.selectedToothId = $(this).data('tooth-id');
                
                // Visual feedback gigi yang dipilih
                $('.tooth-img').css('transform', 'scale(1)');
                $(this).css('transform', 'scale(1.1)');

                // Cek apakah gigi ini sudah ada diagnosis
                let existingRow = null;
                if (window.diagnosisTable) {
                    window.diagnosisTable.rows().every(function() {
                        const rowData = this.data();
                        if (rowData[1] === window.selectedToothId.toString()) {
                            existingRow = this;
                            return false;
                        }
                    });
                }

                if (existingRow) {
                    const diagnosisText = existingRow.data()[2];
                    Swal.fire({
                        title: "Diagnosis Sudah Ada!",
                        html: `Gigi <strong>${window.selectedToothId}</strong> sudah memiliki diagnosis:<br><br><em>${diagnosisText}</em><br><br>Silakan hapus diagnosis yang ada terlebih dahulu jika ingin menggantinya.`,
                        icon: "info",
                        confirmButtonText: "OK"
                    });
                    $('.tooth-img').css('transform', 'scale(1)');
                    window.selectedToothId = null;
                    return;
                }

                $('#tambahDiagnosisModal').modal('show');
            });

            // Klik Tambah Diagnosis (manual) dan menampilkan modal dengan semua field kosong
            $(document).on('click', '#btnTambahDiagnosis', function(e) {
                e.preventDefault();
                window.selectedToothId = null;
                window.editingRow = null;
                $('#tambahDiagnosisModal').modal('show');
            });

            // Event: Modal akan dibuka
            $('#tambahDiagnosisModal').on('show.bs.modal', function() {
                try {
                    $('#nomorGigi').val('').trigger('change');
                    $('#diagnosis').val('').trigger('change');
                    $('#keterangan').val('');
                } catch (error) {
                    // Form reset failed - silent
                }
                
                if (window.selectedToothId) {
                    $('#nomorGigi').val(window.selectedToothId);
                    $('#tambahDiagnosisModalLabel').text('Diagnosis untuk Gigi ' + window.selectedToothId);
                } else {
                    $('#tambahDiagnosisModalLabel').text('Form Tambah Diagnosis');
                }
            });
            
            // Event: Modal selesai dibuka
            $('#tambahDiagnosisModal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    if (window.selectedToothId) {
                        $('#diagnosis').focus();
                    } else {
                        $('#nomorGigi').focus();
                    }
                }, 100);
            });
            
            // Event: Modal ditutup
            $('#tambahDiagnosisModal').on('hidden.bs.modal', function() {
                window.selectedToothId = null;
                $('.tooth-img').css('transform', 'scale(1)');
            });
            
            // Event: Simpan diagnosis
            $(document).on('click', '#simpanDiagnosis', function(e) {
                e.preventDefault();
                
                const nomorGigi = $('#nomorGigi').val();
                const diagnosisId = $('#diagnosis').val();
                const diagnosisText = $('#diagnosis option:selected').text();
                const keterangan = $('#keterangan').val().trim() || '-';

                if (!nomorGigi || nomorGigi === '') {
                    Swal.fire({
                        title: "Peringatan!",
                        html: "Nomor Gigi harus dipilih!",
                        icon: "warning",
                        showCancelButton: false,
                        allowOutsideClick: false
                    }).then(() => {
                        $('#nomorGigi').focus();
                    });
                    return;
                }

                if (!diagnosisId || diagnosisId === '') {
                    Swal.fire({
                        title: "Peringatan!",
                        html: "Diagnosis harus dipilih!",
                        icon: "warning",
                        showCancelButton: false,
                        allowOutsideClick: false
                    }).then(() => {
                        $('#diagnosis').focus();
                    });
                    return;
                }
                
                // Cek duplikasi: Apakah gigi ini sudah ada diagnosis?
                let isDuplicate = false;
                
                if (window.diagnosisTable) {
                    window.diagnosisTable.rows().every(function(index) {
                        const rowData = this.data();
                        
                        if (rowData[1] === nomorGigi) {
                            isDuplicate = true;
                            return false;
                        }
                    });
                }
                
                if (isDuplicate) {
                    Swal.fire({
                        title: "Diagnosis Sudah Ada!",
                        html: `Diagnosis untuk gigi <strong>${nomorGigi}</strong> sudah ada!<br>Silakan edit diagnosis yang sudah ada atau pilih gigi lain.`,
                        icon: "warning",
                        showCancelButton: false,
                        allowOutsideClick: false
                    });
                    return;
                }
                
                // Simpan ke DataTable
                try {
                    if (window.diagnosisTable) {
                        const diagnosisDescription = diagnosisText.includes(' - ') ? diagnosisText.split(' - ')[1] : diagnosisText;
                        
                        const rowData = [
                            '',
                            nomorGigi,
                            diagnosisDescription,
                            keterangan,
                            '<button type="button" class="btn btn-danger btn-sm delete-diagnosis" data-diagnosis-id="' + diagnosisId + '" data-tooth-number="' + nomorGigi + '" title="Hapus"><i class="fa fa-trash"></i></button>'
                        ];

                        window.diagnosisTable.row.add(rowData).draw(false);
                        
                        Swal.fire({
                            title: "Berhasil!",
                            html: `Diagnosis untuk gigi <strong>${nomorGigi}</strong> berhasil ditambahkan!`,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: true
                        });
                        
                        $('#tambahDiagnosisModal').modal('hide');
                        window.selectedToothId = null;

                        if (typeof saveFormData === 'function') {
                            setTimeout(saveFormData, 300);
                        }
                    } else {
                        throw new Error('DataTable not initialized');
                    }
                } catch (error) {
                    Swal.fire({
                        title: "Galat!",
                        html: `Terjadi kesalahan saat menyimpan data:<br><code>${error.message}</code>`,
                        icon: "error",
                        showCancelButton: false,
                        allowOutsideClick: false
                    });
                }
            });

            // Event: Hapus diagnosis
            $('#diagnosisTable tbody').on('click', '.delete-diagnosis', function(e) {
                e.preventDefault();
                
                if (window.diagnosisTable) {
                    const row = window.diagnosisTable.row($(this).closest('tr'));
                    const nomorGigi = row.data()[1];
                    const diagnosisText = row.data()[2];
                    
                    Swal.fire({
                        title: "Hapus Diagnosis?",
                        html: `Apakah Anda yakin ingin menghapus diagnosis:<br><strong>Gigi ${nomorGigi}</strong> - ${diagnosisText}?`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.remove().draw(false);

                            Swal.fire({
                                title: "Terhapus!",
                                html: `Diagnosis gigi <strong>${nomorGigi}</strong> berhasil dihapus.`,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false,
                                allowOutsideClick: true
                            });
                            
                            // Trigger save in form-wizard
                            if (typeof saveFormData === 'function') {
                                setTimeout(saveFormData, 300);
                            }
                        }
                    });
                }
            });

            
            $('#tambahDiagnosisModal').on('keypress', '#nomorGigi, #diagnosis, #keterangan', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    
                    const currentField = $(this).attr('id');
                    
                    switch (currentField) {
                        case 'nomorGigi':
                            if ($(this).val()) $('#diagnosis').focus();
                            break;
                        case 'diagnosis':
                            if ($(this).val()) $('#keterangan').focus();
                            break;
                        case 'keterangan':
                            $('#simpanDiagnosis').click();
                            break;
                    }
                }
            });
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'jQuery atau DataTable tidak tersedia',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }, 500);
});