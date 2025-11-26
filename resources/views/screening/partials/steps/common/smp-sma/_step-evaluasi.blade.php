<x-sweet-alert2.required />

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Evaluasi & Ringkasan Pemeriksaan</h5>
    </div>
    <div class="card-body">

        @php
            $isDoktor = auth()->user()->hasRole('Doktor');
        @endphp

        @if($isDoktor)
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Mohon periksa kembali semua data yang telah Anda masukkan sebelum menyelesaikan proses.
            </div>
            <h6 class="mb-3">Ringkasan Data Pemeriksaan</h6>
        @else
            <div class="alert alert-warning" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Hanya dapat melihat informasi dasar pemeriksaan. Detail medis dapat dilihat oleh Dokter.
            </div>
            <h6 class="mb-3">Informasi Pemeriksaan</h6>
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Siswa</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="40%"><strong>Nama</strong></td>
                                <td>: {{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIS</strong></td>
                                <td>: {{ $student->nis }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>: {{ $current_class->class_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Umur</strong></td>
                                <td>: {{ $age }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Pemeriksaan</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="40%"><strong>Tanggal</strong></td>
                                <td id="review-tanggal_periksa">: {{ isset($mcu) && $mcu && $mcu->date ? \Carbon\Carbon::parse($mcu->date)->isoFormat('D MMMM YYYY') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Periode</strong></td>
                                <td>: {{ $period->name }} {{ $period->month }} {{ $period->year }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dokter Pemeriksa</strong></td>
                                <td id="review-doctor-name">: <span class="text-muted">Loading...</span></td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi</strong></td>
                                <td>: {{ $mcu->location->name ?? $location->name ?? 'Tidak tersedia' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($isDoktor)
            
            <h6 class="mb-3 mt-4">Data Pemeriksaan Lanjutan</h6>

            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-primary">
                        Tanda-tanda Vital
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">1. Tekanan Darah (mmHg)</span>
                                <span id="review-tekanan_darah" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">2. Denyut Nadi (per menit)</span>
                                <span id="review-denyut_nadi" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">3. Frekuensi Nafas (per menit)</span>
                                <span id="review-frekuensi_nafas" class="fw-bold text-dark"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">4. Suhu (°C)</span>
                                <span id="review-suhu" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">5. Bising Jantung</span>
                                <span id="review-bising_jantung" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">6. Bising Paru</span>
                                <span id="review-bising_paru" class="fw-bold text-dark"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-primary">
                        Status Gizi
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">1. Berat Badan (kg)</span>
                                <span id="review-berat_badan" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">2. Tinggi Badan (cm)</span>
                                <span id="review-tinggi_badan" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">3. IMT (BB/TB²)</span>
                                <span id="review-imt" class="fw-bold text-dark"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">4. Status Gizi</span>
                                <span id="review-status_gizi" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">5. TB/U (Stunting)</span>
                                <span id="review-tb_u" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">6. Tanda Klinis Anemi</span>
                                <span id="review-anemi" class="fw-bold text-dark"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-info mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-info">
                        Kebersihan Diri
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">1. Rambut:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-hygiene_rambut" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">2. Kulit Bercak, Keputihan, Kemerahan/Kehitaman:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-kulit_bercak" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-kulit_bercak_ket">-</span></em></p>

                            <p class="mb-1 text-muted">3. Kulit Bersisik:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-kulit_bersisik" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">4. Kulit Ada Memar:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-kulit_memar" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">5. Kulit Ada Sayatan:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-kulit_sayatan" class="text-dark">-</span></p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">6. Kulit Ada Luka Koreng:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-kulit_koreng" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">7. Luka Ada Koreng Sukar Sembuh:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-luka_koreng_sukar" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">8. Kulit Ada Bekas Suntikan:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-kulit_suntikan" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">9. Kuku:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-hygiene_kuku" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-success mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-success">
                       Kesehatan Rongga Mulut
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">1. Celah Bibir/Langit-langit</span>
                                <span id="review-celah_bibir" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">2. Luka Pada Sudut Mulut</span>
                                <span id="review-luka_sudut_mulut" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">3. Sariawan</span>
                                <span id="review-sariawan" class="fw-bold text-dark"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">4. Lidah Kotor</span>
                                <span id="review-lidah_kotor" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">5. Luka Lainnya</span>
                                <span id="review-luka_lainnya" class="fw-bold text-dark"></span>
                            </div>
                            
                            <p class="mb-1 text-muted">6. Masalah Lainnya:</p>
                            <p class="ms-3 mb-0 bg-light p-2 rounded"><span id="review-mulut_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-warning mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Penglihatan</h6>
                            
                            <p class="mb-1 text-muted">1. Mata Luar:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-mata_luar" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">2. Tajam Penglihatan:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-tajam_penglihatan" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-tajam_penglihatan_ket">-</span></em></p>

                            <p class="mb-1 text-muted">3. Buta Warna:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-buta_warna" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">4. Infeksi Mata:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-infeksi_mata" class="text-dark">-</span></p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Pendengaran</h6>
                            
                            <p class="mb-1 text-muted">5. Telinga Luar:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-telinga_luar" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">6. Serumen:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-serumen" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">7. Infeksi Telinga:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-infeksi_telinga" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">8. Masalah Lainnya:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-pendengaran_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-secondary mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-secondary">
                        Kesimpulan & Tindak Lanjut
                    </h6>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted">1. Kesimpulan:</p>
                            <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-kesimpulan" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">2. Saran:</p>
                            <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-saran" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted">3. Follow Up:</p>
                            <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-follow_up" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="alert alert-warning" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong> Setelah menekan tombol <strong>"Selesai & Simpan"</strong>, data akan disimpan
                secara permanen dan pemeriksaan akan ditandai sebagai selesai.
            </div>
        @else
            
        @endif

        <p class="text-muted mb-0 mt-3">
            <small>
                <i class="fa fa-clock me-1"></i>
                Terakhir diupdate: <span id="last-updated-time">Baru dibuat</span>
            </small>
        </p>

    </div>
</div>