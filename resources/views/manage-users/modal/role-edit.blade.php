<div class="modal fade"
     id="modal-revoke-role"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-edit"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cabut Role</h5>
                <button class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form id="form-revoke-role" method="POST">
                <div class="modal-body">
                    @csrf
                    Apakah yakin mencabut role ini dari User?
                    <input type="hidden" id="role-delete-id" name="role" class="form-control"/>
                    <div class="form-group">
                        <label>Role Name</label>
                        <input type="text" id="role-delete-name" class="form-control" disabled/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary"
                            type="button"
                            data-bs-dismiss="modal">
                        <i class="fa fa-close"></i>
                        Close
                    </button>
                    <button class="btn btn-danger btn-submit"
                            type="submit">
                        <i class="fa fa-trash"></i>
                        Cabut
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>