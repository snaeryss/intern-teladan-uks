<x-sweet-alert2.required />

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Evaluasi & Ringkasan Pemeriksaan</h5>
    </div>
    <div class="card-body">

        @php
            $isDoktor = auth()->user()->hasRole('Doktor');
        @endphp

        @if ($isDoktor)
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Mohon periksa kembali semua data yang telah Anda masukkan sebelum
                menyelesaikan proses.
            </div>
            <h6 class="mb-3">Ringkasan Data Pemeriksaan</h6>
        @else
            <div class="alert alert-warning" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Hanya dapat melihat informasi dasar pemeriksaan. Detail medis dapat dilihat
                oleh Dokter.
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
                                <td id="review-tanggal_periksa">:
                                    {{ isset($mcu) && $mcu && $mcu->date ? \Carbon\Carbon::parse($mcu->date)->isoFormat('D MMMM YYYY') : '-' }}
                                </td>
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
                                <td>: {{ $mcu->location->name ?? ($location->name ?? 'Tidak tersedia') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($isDoktor)
            <h6 class="mb-3 mt-4">Data Pemeriksaan Lanjutan</h6>

            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-primary">
                        <i class="fa-solid fa-weight-scale me-2"></i>Pemeriksaan Status Gizi
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Antropometri</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Berat Badan (kg):</span>
                                <span id="review-berat_badan">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tinggi Badan (cm):</span>
                                <span id="review-tinggi_badan">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IMT (BB/TB²):</span>
                                <span id="review-imt">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status Gizi:</span>
                                <span id="review-status_gizi" class="badge bg-secondary">-</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Lingkar Kepala (cm):</span>
                                <span id="review-lingkar_kepala">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Lingkar Lengan Atas (cm):</span>
                                <span id="review-lingkar_lengan_atas">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Lingkar Perut (cm):</span>
                                <span id="review-lingkar_perut">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>BB/U:</span>
                                <span id="review-bb_u">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tanda Klinis Anemia:</span>
                                <span id="review-anemia">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-success">
                                <i class="fa-solid fa-head-side-mask me-2"></i>Pemeriksaan Kepala
                            </h6>
                            
                            <p class="mb-1 text-muted fw-bold">1. Mata:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-mata" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-mata_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">2. Hidung:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-hidung" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-hidung_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">3. Rongga Mulut:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-mulut" class="text-dark">-</span></p>
                            <p class="ms-3 mb-0"><em class="text-muted small">Keterangan: <span id="review-mulut_ket">-</span></em></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-success">
                                <i class="fa-solid fa-lungs me-2"></i>Pemeriksaan Thorax
                            </h6>
                            
                            <p class="mb-1 text-muted fw-bold">1. Jantung:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-jantung" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-jantung_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">2. Paru-paru:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-paru" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-paru_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">3. Neurologi:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-neurologi" class="text-dark">-</span></p>
                            <p class="ms-3 mb-0"><em class="text-muted small">Keterangan: <span id="review-neurologi_ket">-</span></em></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-info">
                                <i class="fa-solid fa-hands-bubbles me-2"></i>Kebersihan Diri
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted fw-bold">1. Rambut:</p>
                                    <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-general_rambut" class="text-dark">-</span></p>
                                    <p class="ms-3 mb-0"><em class="text-muted small">Keterangan: <span id="review-general_rambut_ket">-</span></em></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted fw-bold">2. Kulit:</p>
                                    <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-general_kulit" class="text-dark">-</span></p>
                                    <p class="ms-3 mb-0"><em class="text-muted small">Keterangan: <span id="review-general_kulit_ket">-</span></em></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted fw-bold">3. Kuku:</p>
                                    <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-general_kuku" class="text-dark">-</span></p>
                                    <p class="ms-3 mb-0"><em class="text-muted small">Keterangan: <span id="review-general_kuku_ket">-</span></em></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-warning">
                                <i class="fa-solid fa-eye me-2"></i>Pemeriksaan Penglihatan
                            </h6>
                            
                            <p class="mb-1 text-muted fw-bold">1. Mata Luar:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-mata_luar" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-mata_luar_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">2. Tajam Penglihatan:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-tajam_penglihatan" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-tajam_penglihatan_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">3. Kacamata:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-kacamata" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-kacamata_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">4. Infeksi Mata:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-infeksi_mata" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-infeksi_mata_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">5. Masalah Lainnya:</p>
                            <p class="ms-3 mb-0 bg-light p-2 rounded"><span id="review-penglihatan_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-warning">
                                <i class="fa-solid fa-ear-listen me-2"></i>Pemeriksaan Pendengaran
                            </h6>
                            
                            <p class="mb-1 text-muted fw-bold">1. Telinga Luar:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-telinga_luar" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-telinga_luar_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">2. Serumen:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-serumen" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-serumen_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">3. Infeksi Telinga:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-infeksi_telinga" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-infeksi_telinga_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">4. Tajam Pendengaran:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-tajam_pendengaran" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-tajam_pendengaran_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">5. Masalah Lainnya:</p>
                            <p class="ms-3 mb-0 bg-light p-2 rounded"><span id="review-pendengaran_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-danger">
                                <i class="fa-solid fa-tooth me-2"></i>Kesehatan Rongga Mulut
                            </h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Celah Bibir/Langit-langit:</strong></span>
                                <span id="review-celah_bibir">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Luka Sudut Mulut:</strong></span>
                                <span id="review-luka_sudut_mulut">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Sariawan:</strong></span>
                                <span id="review-sariawan">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Lidah Kotor:</strong></span>
                                <span id="review-lidah_kotor">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><strong>Luka Lainnya:</strong></span>
                                <span id="review-luka_lainnya">-</span>
                            </div>
                            
                            <p class="mb-1 text-muted fw-bold">Masalah Lainnya:</p>
                            <p class="ms-3 mb-0 bg-light p-2 rounded"><span id="review-mulut_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-danger">
                                <i class="fa-solid fa-teeth me-2"></i>Kesehatan Gigi & Gusi
                            </h6>
                            
                            <p class="mb-1 text-muted fw-bold">1. Caries:</p>
                            <p class="ms-3 mb-2 bg-light p-2 rounded"><span id="review-caries" class="text-dark">-</span></p>
                            <p class="ms-3 mb-3"><em class="text-muted small">Keterangan: <span id="review-caries_ket">-</span></em></p>

                            <p class="mb-1 text-muted fw-bold">2. Susunan Gigi Depan Tidak Teratur:</p>
                            <p class="ms-3 mb-3 bg-light p-2 rounded"><span id="review-gigi_depan" class="text-dark">-</span></p>

                            <p class="mb-1 text-muted fw-bold">3. Masalah Lainnya:</p>
                            <p class="ms-3 mb-0 bg-light p-2 rounded"><span id="review-gigi_lainnya" class="text-dark">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-secondary">
                                <i class="fa-solid fa-clipboard-list me-2"></i>Kesimpulan & Tindak Lanjut
                            </h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1 text-muted fw-bold">1. Kesimpulan:</p>
                                    <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-kesimpulan" class="text-dark">-</span></p>

                                    <p class="mb-1 text-muted fw-bold">2. Saran:</p>
                                    <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-saran" class="text-dark">-</span></p>

                                    <p class="mb-1 text-muted fw-bold">3. Follow Up:</p>
                                    <p class="ms-3 mb-3 bg-light p-3 rounded"><span id="review-follow_up" class="text-dark">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="alert alert-warning" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong> Setelah menekan tombol <strong>"Selesai & Simpan"</strong>, data akan
                disimpan
                secara permanen dan pemeriksaan akan ditandai sebagai selesai.
            </div>
        @else
        @endif

        <p class="text-muted mb-0 mt-3">
            <small>
                <i class="fa fa-clock me-1"></i>
                Terakhir diupdate: <span
                    id="last-updated-time">{{ isset($mcu) && $mcu ? $mcu->updated_at->diffForHumans() : 'Baru dibuat' }}</span>
            </small>
        </p>

    </div>
</div>