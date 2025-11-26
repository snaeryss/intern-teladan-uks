<div class="modal fade"
     id="modal-edit"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-edit"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Lokasi Pemeriksaan</h5>
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
                                <label for="name_edit" class="form-label">Nama Lokasi</label>
                                <input class="form-control"
                                       type="text"
                                       id="name_edit"
                                       name="name"
                                       placeholder="Contoh: Ruang UKS Lantai 1"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="is_active_edit" class="form-label">Status</label>
                                <select class="form-control"
                                        id="is_active_edit"
                                        name="is_active"
                                        required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
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