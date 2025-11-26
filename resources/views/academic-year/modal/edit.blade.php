@php
    use App\Enums\AcademicYearStatusEnum;
@endphp
<div class="modal fade"
     id="modal-edit"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-edit"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit {{ $title }}</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form id="form-edit" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="year_start" class="form-label">Tahun Mulai</label>
                                <input class="form-control"
                                       type="text"
                                       id="year_start"
                                       name="year_start"
                                       placeholder="Tahun Selesai"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="year_end" class="form-label">Tahun Selesai</label>
                                <input class="form-control"
                                       type="text"
                                       id="year_end"
                                       name="year_end"
                                       placeholder="Tahun Selesai"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-control"
                                        id="is_active"
                                        name="is_active"
                                        required>
                                    <option value="{{ AcademicYearStatusEnum::ACTIVE }}">Active</option>
                                    <option value="{{ AcademicYearStatusEnum::INACTIVE }}">Inactive</option>
                                </select>
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
