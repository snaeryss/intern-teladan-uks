<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Diagnosis Gigi</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id" value="{{ old('id') }}">
                <div class="modal-body">
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="edit_code" class="form-label">
                                    Kode Diagnosis <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('code') is-invalid @enderror" type="text" id="edit_code" name="code" placeholder="Contoh: K02.1" value="{{ old('code') }}" maxlength="10" required>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description" name="description" rows="4" placeholder="Masukkan deskripsi diagnosis" maxlength="1000" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
