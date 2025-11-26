@extends("layouts.main")

<x-sweet-alert2.required/>

@section('content')
    @include('components.content-title', ['active' => 'Account', 'menus' => ['Account']])
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-12">
                <form class="card" method="POST" action="{{ route('account.update') }}">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row custom-input">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $user->username }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input class="form-control"
                                           name="name"
                                           type="text"
                                           value="{{ $user->name }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <hr/>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Old Password</label>
                                    <input class="form-control @error('old_password') is-invalid @enderror"
                                           type="password"
                                           name="old_password"
                                           placeholder="Type old password...">
                                    @error('old_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        New Password
                                    </label>
                                    <input class="form-control @error('password') is-invalid @enderror"
                                           type="password"
                                           name="password"
                                           placeholder="Type new password...">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small>*Fill when you want to change password</small>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-save"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Roles</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="table-success">
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Description
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ Str::limit($role->description, 36) }}</td>
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
                    <h4 class="card-title mb-0">Person </h4>
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
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
@endsection
