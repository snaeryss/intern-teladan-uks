@php
    use App\Enums\CheckUpTypeEnum;
@endphp

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-title">Tambah Periode</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('periods.store', $academicYear->id) }}" method="POST" id="form-period">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select js-example-basic-single" id="name" name="name" required>
                            <option value="" disabled selected>— Pilih Kegiatan —</option>
                            @foreach($checkupTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Nama kegiatan wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="month">Bulan <span class="text-danger">*</span></label>
                        <select class="form-select js-example-basic-single" id="month" name="month" required>
                            <option value="" disabled selected>— Pilih Bulan —</option>
                            @foreach($months as $month)
                                <option value="{{ $month->value }}">{{ $month->value }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Bulan wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="year">Tahun <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control" 
                               id="year" 
                               name="year" 
                               placeholder="Masukkan tahun (contoh: 2025)"
                               min="2020" 
                               max="2100" 
                               required>
                        <small class="text-muted">Tahun akademik saat ini: {{ $academicYear->year_start }}/{{ $academicYear->year_end }}</small>
                        <div class="invalid-feedback">Tahun wajib diisi (2020-2100).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times"></i> Batal
                    </button>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>