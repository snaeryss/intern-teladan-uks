@php
    use App\Enums\Student\Gender;
@endphp

@extends("layouts.main")

<x-sweet-alert2.required/>

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => 'Detail', 'menus' => ['Students', 'Classes', 'Detail']])
    <div id="myModal"
         class="modal fade"
         role="dialog"
         tabindex="-1"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">
                        Create Mass Account
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('student.multiple.account.store') }}"
                      method='POST'>
                    @csrf
                    <div class="modal-body">
                        <p>
                            This action will create mass account for all students on this classes,
                            are you sure to do this action?<br/>
                            <b>This might take a while...</b>
                        </p>
                        <input type="hidden"
                               name="class_level"
                               value="{{ $classLevel }}">
                        <input type="hidden"
                               name="class_name"
                               value="{{ $className }}">
                        <input type="hidden"
                               name="group_year"
                               value="{{ $groupYear }}">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                type="button"
                                data-bs-dismiss="modal">
                            <i class="fa fa-close"></i>
                            Close
                        </button>
                        <button class="btn-submit btn btn-success waves-effect waves-light">
                            <i class="fa fa-users"></i>
                            Sure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade"
         id="exportModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exportModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">
                        Export Accounts
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('student.multiple.account.export') }}"
                      method='POST'>
                    @csrf
                    <div class="modal-body">
                        <p>
                            This action will export all student accounts on this classes. <b>This might take a
                                while...</b>,
                            are you sure to do this action?<br/>
                        </p>
                        <input type="hidden"
                               name="class_level"
                               value="{{ $classLevel }}">
                        <input type="hidden"
                               name="class_name"
                               value="{{ $className }}">
                        <input type="hidden"
                               name="group_year"
                               value="{{ $groupYear }}">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                type="button"
                                data-bs-dismiss="modal">
                            <i class="fa fa-close"></i>
                            Close
                        </button>
                        <button class="btn-submit btn btn-primary waves-effect waves-light">
                            <i class="fa fa-download"></i>
                            Sure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border pb-2">
                    <button data-bs-toggle="modal"
                            data-bs-target="#exportModal"
                            type="button"
                            class="btn btn-secondary waves-effect waves-light"
                            style="margin-right:10px; float:right;">
                        <i class="fa fa-download"></i>
                        Export Account
                    </button>
                    <button data-bs-toggle="modal"
                            data-bs-target="#myModal"
                            type="button"
                            class="btn btn-success waves-effect waves-light"
                            style="margin-right:10px; float:right;">
                        <i class="fa fa-users"></i>
                        Create Mass Account
                    </button>
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input class="form-control"
                                       type="text"
                                       value="{{ $classLevel }}{{ $className }}"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tahun Akademik</label>
                                <input class="form-control"
                                       type="text"
                                       value="{{ $groupYear }}"
                                       disabled>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive add-project custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Angkatan</th>
                                <th style="width: 145px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($students as $index => $student)
                                <tr>
                                    <td class="text-center"><b>{{ $index + 1 }}.</b></td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        @if($student->sex === Gender::MALE)
                                            Laki-laki
                                        @elseif($student->sex === Gender::FEMALE)
                                            Perempuan
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $student->date_birth_text }}</td>
                                    <td>{{ $student->group_year }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('student.detail', $student) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-arrow-right-circle"
                                                 style="width: 16px;vertical-align: middle;">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 16 16 12 12 8"></polyline>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
@endsection
