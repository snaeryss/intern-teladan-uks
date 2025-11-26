<x-sweet-alert2.required />

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Pemeriksaan Lanjutan</h5>
    </div>
    <div class="card-body">

        <div class="mb-4">
            <h6 class="mb-2">Oklusi</h6>
            <textarea class="form-control" name="oklusi" id="oklusi" rows="3" placeholder="Jelaskan kondisi oklusi...">{{ $dcu->examination->occlusion ?? '' }}</textarea>
        </div>

        <div class="mb-4">
            <h6 class="mb-2">Mukosa</h6>
            <textarea class="form-control" name="mukosa" id="mukosa" rows="3" placeholder="Jelaskan kondisi mukosa...">{{ $dcu->examination->mucosal_notes ?? '' }}</textarea>
        </div>

        <div class="mb-4">
            <h6 class="mb-2">DMF</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="dmf_d" class="form-label">D (Decay)</label>
                    <input type="number" class="form-control" id="dmf_d" name="dmf_d" min="0"
                        value="{{ $dcu->examination->decayed_teeth ?? 0 }}" step="0.1">
                </div>
                <div class="col-md-4">
                    <label for="dmf_m" class="form-label">M (Missing)</label>
                    <input type="number" class="form-control" id="dmf_m" name="dmf_m" min="0"
                        value="{{ $dcu->examination->missing_teeth ?? 0 }}" step="0.1">
                </div>
                <div class="col-md-4">
                    <label for="dmf_f" class="form-label">F (Filling)</label>
                    <input type="number" class="form-control" id="dmf_f" name="dmf_f" min="0"
                        value="{{ $dcu->examination->filled_teeth ?? 0 }}" step="0.1">
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="mb-3">Frekuensi Menyikat Gigi dalam 1 Hari</h6>
            <div class="d-flex gap-4 flex-wrap">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frekuensi_sikat" id="sikat1x" value="1x" 
                        {{ ($dcu->examination->brushing_frequency ?? '') == '1x' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sikat1x">1x</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frekuensi_sikat" id="sikat2x" value="2x"
                        {{ ($dcu->examination->brushing_frequency ?? '') == '2x' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sikat2x">2x</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frekuensi_sikat" id="sikat3x" value="3x"
                        {{ ($dcu->examination->brushing_frequency ?? '') == '3x' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sikat3x">3x</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frekuensi_sikat" id="sikatLebih" value="Lebih dari 3x"
                        {{ ($dcu->examination->brushing_frequency ?? '') == 'Lebih dari 3x' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sikatLebih">Lebih dari 3x</label>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="mb-3">Waktu Menggosok Gigi</h6>
            @php
                $waktuOptions = [
                    'Pagi Sebelum Makan',
                    'Mandi Pagi dan Sore',
                    'Malam Sebelum Tidur',
                    'Pagi sebelum Makan dan Malam Sebelum Tidur'
                ];
            @endphp
            @foreach ($waktuOptions as $index => $time)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="waktu_sikat" id="waktu{{ $index }}" value="{{ $time }}"
                        {{ ($dcu->examination->brushing_time ?? '') == $time ? 'checked' : '' }}>
                    <label class="form-check-label" for="waktu{{ $index }}">{{ $time }}</label>
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <h6 class="mb-3">Penggunaan Pasta Gigi</h6>
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pasta_gigi" id="pastaYa" value="Ya"
                        {{ ($dcu->examination->uses_toothpaste ?? '') == 'Ya' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pastaYa">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pasta_gigi" id="pastaTidak" value="Tidak"
                        {{ ($dcu->examination->uses_toothpaste ?? '') == 'Tidak' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pastaTidak">Tidak</label>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="mb-3">Konsumsi Makanan Manis</h6>
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="makanan_manis" id="manisYa" value="Ya"
                        {{ ($dcu->examination->consumes_sweets ?? '') == 'Ya' ? 'checked' : '' }}>
                    <label class="form-check-label" for="manisYa">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="makanan_manis" id="manisTidak" value="Tidak"
                        {{ ($dcu->examination->consumes_sweets ?? '') == 'Tidak' ? 'checked' : '' }}>
                    <label class="form-check-label" for="manisTidak">Tidak</label>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="mb-5">
            <h6 class="mb-3 fs-5">Pemeriksaan OHI-S</h6>

            <div class="mb-4">
                <h6 class="mb-3 text-primary">DI (Debris Index)</h6>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_1_1"
                                            name="di_matrix[0][0]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_1_1 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_1_2"
                                            name="di_matrix[0][1]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_1_2 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_1_3"
                                            name="di_matrix[0][2]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_1_3 ?? 0 }}" placeholder="0">
                                    </div>
                                </div>
                                <div class="row g-2 mt-1">
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_2_1"
                                            name="di_matrix[1][0]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_2_1 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_2_2"
                                            name="di_matrix[1][1]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_2_2 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center di-input" id="di_2_3"
                                            name="di_matrix[1][2]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->di_2_3 ?? 0 }}" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div>
                            <div>
                                <label for="skor_di" class="form-label fw-bold">Skor DI</label>
                                <input type="number" class="form-control bg-body-secondary" id="skor_di"
                                    name="skor_di" readonly value="{{ $dcu->ohis->di_score ?? 0.00 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 text-success">CI (Calculus Index)</h6>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_1_1"
                                            name="ci_matrix[0][0]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_1_1 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_1_2"
                                            name="ci_matrix[0][1]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_1_2 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_1_3"
                                            name="ci_matrix[0][2]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_1_3 ?? 0 }}" placeholder="0">
                                    </div>
                                </div>
                                <div class="row g-2 mt-1">
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_2_1"
                                            name="ci_matrix[1][0]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_2_1 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_2_2"
                                            name="ci_matrix[1][1]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_2_2 ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm text-center ci-input" id="ci_2_3"
                                            name="ci_matrix[1][2]" min="0" max="3" step="0.1" value="{{ $dcu->ohis->ci_2_3 ?? 0 }}" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div>
                            <div>
                                <label for="skor_ci" class="form-label fw-bold">Skor CI</label>
                                <input type="number" class="form-control bg-body-secondary" id="skor_ci"
                                    name="skor_ci" readonly value="{{ $dcu->ohis->ci_score ?? 0.00 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="skor_ohis" class="form-label fw-bold">Skor OHI-S</label>
                        <input type="number" class="form-control bg-body-secondary mt-2" id="skor_ohis"
                            name="skor_ohis" readonly value="{{ $dcu->ohis->ohis_score ?? 0.00 }}">
                    </div>
                    <div class="col-md-6">
                        <label for="status_ohis" class="form-label fw-bold">Status OHI-S</label>
                        <input type="text" class="form-control bg-body-secondary mt-2" id="status_ohis"
                            name="status_ohis" readonly value="{{ $dcu->ohis->ohis_status ?? '' }}">
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-body-secondary">
                        <strong>* Panduan Status:</strong>
                        0,1 - 1,2 = Baik | 1,3 - 3,0 = Sedang | 3,1 - 6,0 = Buruk
                    </small>
                </div>
            </div>

            <div class="mb-4">
                <label for="ohis_keterangan" class="form-label fw-bold">Keterangan</label>
                <textarea class="form-control mt-2" id="ohis_keterangan" name="ohis_keterangan" rows="3"
                    placeholder="Tambahkan keterangan atau observasi terkait pemeriksaan OHI-S...">{{ $dcu->ohis->notes ?? '' }}</textarea>
            </div>
        </div>

    </div>
</div>
