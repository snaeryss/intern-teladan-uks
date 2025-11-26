<div class="modal fade"
     id="modal-create"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-create"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Buat Lokasi Pemeriksaan</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form id="form-create" action="{{ route('locations.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="name_create" class="form-label">Nama Lokasi</label>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       id="name_create"
                                       name="name"
                                       placeholder="Contoh: Ruang UKS Lantai 1"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="is_active_create" class="form-label">Status</label>
                                <select class="form-control @error('is_active') is-invalid @enderror"
                                        id="is_active_create"
                                        name="is_active"
                                        required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary"
                            type="button"
                            data-bs-dismiss="modal">
                        <i class="fa fa-close"></i>
                        Close
                    </button>
                    <button class="btn btn-success btn-submit"
                            type="submit">
                        <i class="fa fa-save"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>