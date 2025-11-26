<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Buat Diagnosis Gigi</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create" action="{{ route('dental-diagnoses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="code" class="form-label">
                                    Kode Diagnosis <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('code') is-invalid @enderror" type="text" id="code" name="code" placeholder="Contoh: K02.1" value="{{ old('code') }}" maxlength="10" required>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 10 karakter</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Masukkan deskripsi diagnosis" maxlength="1000" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 1000 karakter</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Close
                    </button>
                    <button class="btn btn-success btn-submit" type="submit">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
