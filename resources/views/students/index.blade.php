@php
    use App\Enums\Student\Gender;
    use App\Enums\Student\Status as EnumsStudentStatus;
@endphp

@extends("layouts.main")

<x-datatables.required/>

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => [$title]])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                               id="datatable">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Angkatan</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td class="text-center" style="font-weight: bold;"></td>
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
                                    <td class="text-center">
                                        @if($student->status === EnumsStudentStatus::ACTIVE)
                                            <span class="badge badge-success rounded-pill p-2">Active</span>
                                        @else
                                            <span class="badge badge-secondary rounded-pill p-2">Inactive</span>
                                        @endif
                                    </td>
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <script>
        $(document).ready(function () {
            let table = $("#datatable").DataTable({
                "pageLength": 50,
                "order": [[0, 'asc']],
                "columnDefs": [
                    {"orderable": false, "targets": 4}

                ],
                "createdRow": function (row, data, dataIndex) {
                    $('td:eq(0)', row).html(dataIndex + 1 + ".");
                }
            });
            table.on('draw.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = (i + 1) + ".";
                });
            });
            console.log(table);
        });
    </script>
@endsection
