<div class="modal fade"
     id="modal-add-role"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-create"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Role</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row custom-input">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <select class="form-control"
                                        type="text"
                                        name="role"
                                        required>
                                    <option> :: pilih Role ::</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }} | {{ Str::limit($role->description, 72) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <small><b><i>*Lihat halaman Roles untuk melihat detail deskripsi role</i></b></small>
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