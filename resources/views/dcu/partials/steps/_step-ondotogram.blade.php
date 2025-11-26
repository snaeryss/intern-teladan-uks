@php
    $toothImages = [
        18 => 'normal1', 17 => 'normal1', 16 => 'normal1', 15 => 'normal1', 14 => 'normal1',
        13 => 'normal2', 12 => 'normal2', 11 => 'normal2', 21 => 'normal2', 22 => 'normal2',
        23 => 'normal2', 24 => 'normal1', 25 => 'normal1', 26 => 'normal1', 27 => 'normal1',
        28 => 'normal1', 55 => 'normal1', 54 => 'normal1', 53 => 'normal2', 52 => 'normal2',
        51 => 'normal2', 61 => 'normal2', 62 => 'normal2', 63 => 'normal2', 64 => 'normal1',
        65 => 'normal1', 85 => 'normal1', 84 => 'normal1', 83 => 'normal2', 82 => 'normal2',
        81 => 'normal2', 71 => 'normal2', 72 => 'normal2', 73 => 'normal2', 74 => 'normal1',
        75 => 'normal1', 48 => 'normal1', 47 => 'normal1', 46 => 'normal1', 45 => 'normal1',
        44 => 'normal1', 43 => 'normal2', 42 => 'normal2', 41 => 'normal2', 31 => 'normal2',
        32 => 'normal2', 33 => 'normal2', 34 => 'normal1', 35 => 'normal1', 36 => 'normal1',
        37 => 'normal1', 38 => 'normal1',
    ];
@endphp

<x-datatables.required />
<x-sweet-alert2.required />

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Formulir Pemeriksaan Gigi (Odontogram)</h5>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <h6 class="mb-2">Informasi Awal</h6>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="dcu_date" class="form-label">Tanggal Periksa <span class="text-danger">*</span></label>
                    <input type="date" 
                        class="form-control" 
                        id="dcu_date" 
                        name="dcu_date"
                        value="{{ $dcu->date ? $dcu->date->format('Y-m-d') : date('Y-m-d') }}"
                        max="{{ date('Y-m-d') }}">
                    <small class="text-muted">Default: Hari ini</small>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="umurSaatPeriksa" class="form-label">Umur Saat Periksa</label>
                    <input type="text" class="form-control" id="umurSaatPeriksa"
                        value="{{ $age ?? 'Data tidak tersedia' }}" readonly>
                </div>
            </div>
        </div>
        <hr>

        <h6 class="mb-3">Form Ondotogram</h6>
        <div class="odontogram-grid mx-auto">
            {{-- Rahang Atas --}}
            <div class="tooth-row">
                @foreach (range(18, 11) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
                @foreach (range(21, 28) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
            </div>
            {{-- Rahang Atas Susu --}}
            <div class="tooth-row">
                @foreach (range(55, 51) as $i)
                     <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
                <div class="tooth-placeholder"></div>
                @foreach (range(61, 65) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
            </div>
            {{-- Rahang Bawah Susu --}}
            <div class="tooth-row mt-3">
                 @foreach (range(85, 81) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
                <div class="tooth-placeholder"></div>
                @foreach (range(71, 75) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
            </div>
            {{-- Rahang Bawah Permanen --}}
            <div class="tooth-row">
                @foreach (range(48, 41) as $i)
                    <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
                @foreach (range(31, 38) as $i)
                     <div class="tooth-wrapper text-center">
                        <div class="fw-bold tooth-number">{{ $i }}</div>
                        <img src="{{ asset('images/dental/' . $toothImages[$i] . '.png') }}" 
                             alt="Gigi {{ $i }}" 
                             title="Klik untuk diagnosis gigi {{ $i }}" 
                             class="tooth-img" 
                             data-tooth-id="{{ $i }}" 
                             style="cursor: pointer; width: 40px; height: 40px; transition: transform 0.2s;">
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">List Diagnosis</h6>
            <div>
                <button class="btn btn-primary" id="btnTambahDiagnosis" type="button">
                    <i class="fa fa-plus me-2"></i>Tambah Diagnosis
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="diagnosisTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Nomor Gigi</th>
                        <th>Diagnosis</th>
                        <th>Keterangan</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahDiagnosisModal" tabindex="-1" aria-labelledby="tambahDiagnosisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahDiagnosisModalLabel">Form Tambah Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nomorGigi" class="form-label">Nomor Gigi</label>
                    <select class="form-select" id="nomorGigi" required>
                        <option selected disabled value="">Pilih Nomor Gigi...</option>
                        <optgroup label="Permanent - Atas">
                            @foreach (array_merge(range(18, 11), range(21, 28)) as $gigi)
                                <option value="{{ $gigi }}">{{ $gigi }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Deciduous - Atas">
                            @foreach (array_merge(range(55, 51), range(61, 65)) as $gigi)
                                <option value="{{ $gigi }}">{{ $gigi }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Permanent - Bawah">
                            @foreach (array_merge(range(48, 41), range(31, 38)) as $gigi)
                                <option value="{{ $gigi }}">{{ $gigi }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Deciduous - Bawah">
                            @foreach (array_merge(range(85, 81), range(71, 75)) as $gigi)
                                <option value="{{ $gigi }}">{{ $gigi }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="diagnosis" class="form-label">Diagnosis</label>
                    <select class="form-select" id="diagnosis" required>
                        <option selected disabled value="">Pilih Diagnosis...</option>
                        @foreach($dentalDiagnoses as $dd)
                            <option value="{{ $dd->id }}" data-code="{{ $dd->code }}">
                                {{ $dd->code }} - {{ $dd->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" rows="3" placeholder="Tambahkan keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="simpanDiagnosis">
                    <i class="fa fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.tooth-img:hover {
    transform: scale(1.1) !important;
    filter: brightness(1.1);
}

.tooth-wrapper {
    margin: 2px;
    display: inline-block;
}

.tooth-number {
    font-size: 12px;
    margin-bottom: 4px;
}

.odontogram-grid {
    max-width: 800px;
}

.tooth-row {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.tooth-placeholder {
    width: 44px;
    height: 44px;
    margin: 2px;
}
</style>

<script>
    @if($dcu->diagnoses && $dcu->diagnoses->count() > 0)
        window.existingDiagnoses = {!! json_encode($dcu->diagnoses->map(fn($d) => [
            'tooth_number' => $d->tooth_number,
            'dental_diagnosis_id' => $d->dental_diagnosis_id,
            'code' => $d->dentalDiagnosis->code,
            'description' => $d->dentalDiagnosis->description,
            'notes' => $d->notes
        ])) !!};
    @else
        window.existingDiagnoses = [];
    @endif
</script>