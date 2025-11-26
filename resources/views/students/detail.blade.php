@php
    use App\Enums\Student\Gender;
    use App\Enums\Student\Status as EnumsStudentStatus;
@endphp

@extends("layouts.main")

<x-sweet-alert2.required/>

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => 'Detail', 'menus' => ['Students', 'Detail']])
    <div class="modal fade"
         id="modal-create"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-create"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Student Account</h5>
                    <button class="btn-close"
                            type="button"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('student.account.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row custom-input">
                            <div class="col-lg-12">
                                <b>Apakah yakin membuat akun untuk siswa ini?</b>
                                <div class="mb-3">
                                    <input type="hidden" name="student"
                                           value="{{ $student->id }}"/>
                                    <label class="form-label">Nama</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $student->name }}"
                                           disabled>
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
                            <i class="fa fa-add"></i>
                            Buat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('student.update', $student) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header card-no-border pb-2">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIS</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $student->nis }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Angkatan</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $student->group_year }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input class="form-control @error('name') is-invalid @enderror"
                                           type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $student->name) }}"
                                           required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_birth" class="form-label">Tanggal Lahir</label>
                                    <input class="form-control @error('date_birth') is-invalid @enderror"
                                           type="date"
                                           id="date_birth"
                                           name="date_birth"
                                           value="{{ old('date_birth', $student->date_birth->format('Y-m-d')) }}"
                                           required>
                                    @error('date_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sex" class="form-label">Jenis Kelamin</label>
                                    <select class="form-control @error('sex') is-invalid @enderror"
                                            id="sex"
                                            name="sex"
                                            required>
                                        <option @selected(old('sex', $student->sex) === Gender::MALE)
                                                value="{{ Gender::MALE }}">
                                            Laki-laki
                                        </option>
                                        <option @selected(old('sex', $student->sex) === Gender::FEMALE)
                                                value="{{ Gender::FEMALE }}">
                                            Perempuan
                                        </option>
                                    </select>
                                    @error('sex')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenjang</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $student->school_level->name }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ $student->status === EnumsStudentStatus::ACTIVE ? 'Aktif' : 'Tidak Aktif' }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(empty($account))
                                    <a data-uid="{{ $student->id }}"
                                       class="btn btn-info waves-effect waves-light btn-create"
                                       style="float:right;"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modal-create">
                                        <i class="fa fa-plus"></i>
                                        Create Account
                                    </a>
                                @else
                                    <a data-uid="{{ $student->id }}"
                                       class="btn btn-secondary waves-effect waves-light btn-show"
                                       href="#">
                                        <i class="fa fa-search-plus"></i>
                                        See Account
                                    </a>
                                    <a data-uid="{{ $student->id }}"
                                       class="btn btn-warning waves-effect waves-light btn-reset"
                                       href="#">
                                        <i class="fa fa-sync-alt"></i>
                                        Reset Account
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a class="btn btn-secondary" href="{{ route('student') }}">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                        <button class="btn btn-success btn-submit" type="submit">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    <script>
        let btnShow =
            $(".btn-show").on("click", function () {
                const id = $(this).data("uid");
                $.ajax({
                    url: "{{ route('student.account.show', ':id') }}".replace(":id", id),
                    method: 'get',
                    success: function (data) {
                        let user = data.data;
                        let msg = "<div style='text-align: center;'>" +
                            "<table style='margin: 0 auto;'>" +
                            "<tr><td>username</td><td>:</td><th class='text-start'>" + user.username + "</th></tr>" +
                            "<tr><td>password</td><td>:</td><th class='text-start'>" + user.uncrypted + "</th></tr>" +
                            "</table></div>";
                        Swal.fire({
                            title: "Success!",
                            html: msg,
                            icon: "success",
                            allowOutsideClick: false,
                            showCancelButton: 0,
                            timer: 10000
                        });
                    },
                    error: function () {
                        Swal.fire({
                            title: "Fail!",
                            html: "Gagal Mengambil data",
                            icon: "error",
                            allowOutsideClick: false,
                            showCancelButton: 0,
                        });
                    }
                });
            });
    </script>
@endsection
