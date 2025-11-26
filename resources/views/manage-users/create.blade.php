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
                <form action="{{ route('manage-account.store') }}"
                      method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror"
                                       type="text"
                                       name="username"
                                       value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success btn-submit mb-3"
                                style="float:right;"
                                type="submit">
                            <i class="fa fa-save"></i>
                            Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
@endsection
