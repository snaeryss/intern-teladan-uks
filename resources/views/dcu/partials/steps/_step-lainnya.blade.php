<x-sweet-alert2.required />

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Evaluasi Pemeriksaan</h5>
    </div>
    <div class="card-body">

        @php
            $isDokterGigi = auth()->user()->hasRole('Doktor Gigi');
        @endphp

        @if($isDokterGigi)
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Pastikan semua data telah diisi dengan benar.
            </div>
            <h6 class="mb-3">Ringkasan Data Pemeriksaan</h6>
        @else
            <div class="alert alert-warning" role="alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Hanya dapat melihat informasi dasar pemeriksaan. Detail medis dapat dilihat oleh Dokter Gigi.
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
                                <td>: {{ $dcu->date ? \Carbon\Carbon::parse($dcu->date)->isoFormat('D MMMM YYYY') : '-' }}</td>
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
                                <td>: {{ $dcu->location->name ?? $location->name ?? 'Tidak tersedia' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($isDokterGigi)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-primary">
                                <i class="fa fa-tooth me-2"></i>Diagnosis Gigi
                            </h6>
                            <div id="review-diagnosis-summary">
                                <small class="text-muted">Loading...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="mb-3 mt-4">Data Pemeriksaan Lanjutan</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-primary">Oklusi & Mukosa</h6>
                            <div class="mb-2">
                                <strong>Oklusi:</strong>
                                <p class="ms-3 mb-1" id="review-oklusi">-</p>
                            </div>
                            <div class="mb-2">
                                <strong>Mukosa:</strong>
                                <p class="ms-3 mb-1" id="review-mukosa">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-primary">DMF</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>D (Decay):</strong></span>
                                <span id="review-dmf_d">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>M (Missing):</strong></span>
                                <span id="review-dmf_m">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>F (Filling):</strong></span>
                                <span id="review-dmf_f">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-info">Kebiasaan Menyikat Gigi</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Frekuensi:</strong></span>
                                <span id="review-frekuensi_sikat">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Waktu:</strong>
                                <p id="review-waktu_sikat">-</p>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Pasta Gigi:</strong></span>
                                <span id="review-pasta_gigi">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Makanan Manis:</strong></span>
                                <span id="review-makanan_manis">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-success">
                                <i class="fa fa-heartbeat me-2"></i>Status OHI-S
                            </h6>
                            <div id="review-ohis-summary">
                                <small class="text-muted">Loading...</small>
                            </div>
                            <hr>
                            <div class="mb-2">
                                <strong>Keterangan:</strong>
                                <p class="ms-3 mb-0 small" id="review-ohis_keterangan">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-success">Detail Skor OHI-S</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Skor DI (Debris Index):</strong>
                                    <span id="review-skor_di" class="ms-2">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Skor CI (Calculus Index):</strong>
                                    <span id="review-skor_ci" class="ms-2">-</span>
                                </div>
                            </div>
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
                Terakhir diupdate: {{ $dcu->updated_at ? $dcu->updated_at->diffForHumans() : 'Baru dibuat' }}
            </small>
        </p>

    </div>
</div>