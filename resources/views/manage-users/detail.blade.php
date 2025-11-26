@php
    use Illuminate\Support\Str;
	use Illuminate\Support\Js;
@endphp

@extends("layouts.main")

<x-sweet-alert2.required/>

@section('content')
    @include('components.content-title', ['active' => 'Detail', 'menus' => ['Accounts', 'Detail']])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <h4 class="card-title mb-0">Accounts</h4>
                </div>
                <form action="{{ route('manage-account.update', $user->id) }}"
                      method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input class="form-control"
                                       type="text"
                                       value="{{ $user->username }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ $user->name }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror"
                                        name="status"
                                        required>
                                    <option value="" disabled selected>
                                        :: Pilih Status ::
                                    </option>
                                    <option value="1"
                                            {{ $user->is_active == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                            {{ $user->is_active == 0 ? 'selected' : ''}}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small><b><i>*hanya isi ketika mengganti password</i></b></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success btn-submit mb-3"
                                style="float:right;"
                                type="submit">
                            <i class="fa fa-save"></i>
                            Simpan
                        </button>
                        <a class="btn btn-secondary btn-submit mb-3 m-r-5"
                           href="{{ route('manage-account') }}"
                           style="float:right;">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary"
                            style="float:right;"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-add-role"
                            type="button">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-plus-circle" style="width: 16px;vertical-align: middle;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Role
                    </button>
                    <h4 class="card-title mb-0">Roles</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success">
                        <tr>
                            <th>
                                Nama
                            </th>
                            <th>
                                Deskripsi
                            </th>
                            <td style="width: 10px;">

                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ Str::limit($role->description, 36) }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm"
                                            style="padding: 0.25rem 0.5rem"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-revoke-role"
                                            onclick="revokeRole({{ Js::from($role) }})"
                                            type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="16" height="16" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="feather feather-trash-2"
                                             style="width: 16px;vertical-align: middle;">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Person</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success">
                        <tr>
                            <th style="width: 24px;">
                                Name
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Role
                            </th>
                            <td style="width: 10px;">

                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('manage-users.modal.role-add')
    @include('manage-users.modal.role-edit')
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    <script>
        let formRemoveRole = $('#form-revoke-role');
        const revokeRole = (data) => {
            $('#role-delete-id').val(data.id);
            $('#role-delete-name').val(data.name);
        };
        let formRemoveSubject = $('#form-revoke-subject');
        const revokeSubject = (data) => {
            $('#subject-delete-id').val(data.id);
            $('#subject-delete-class').val(data.class_level);
            $('#subject-delete-name').val(data.subject.name);
        };
    </script>
@endsection
