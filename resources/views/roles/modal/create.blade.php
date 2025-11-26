<div class="modal fade"
     id="modal-create"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-create"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Buat Role</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          type="text"
                                          name="description"
                                          rows="3"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
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