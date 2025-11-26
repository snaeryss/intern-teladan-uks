<div class="modal fade"
     id="modal-create"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-create"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Buat {{ $title }}</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form id="form-create" action="{{ route('academic-year.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="year_start_create" class="form-label">Tahun Mulai</label>
                                <input class="form-control @error('year_start') is-invalid @enderror"
                                       type="text"
                                       id="year_start_create"
                                       name="year_start"
                                       placeholder="Tahun Mulai"
                                       value="{{ old('year_start') }}"
                                       required>
                                @error('year_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="year_end_create" class="form-label">Tahun Selesai</label>
                                <input class="form-control @error('year_end') is-invalid @enderror"
                                       type="text"
                                       id="year_end_create"
                                       name="year_end"
                                       placeholder="Tahun Selesai"
                                       value="{{ old('year_end') }}"
                                       required>
                                @error('year_end')
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