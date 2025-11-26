@extends("layouts.main")

<x-datatables.required/>

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Laporan', '...', $title]])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                        <div>
                            <a href="{{ route('reports.activity.detail', ['id' => $academicYear->id, 'activity' => $activity]) }}" 
                               class="btn btn-light btn-sm">
                                <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                                Kembali
                            </a>
                            @if($students->isNotEmpty())
                                <form action="{{ route('reports.student.list.export', ['id' => $academicYear->id, 'activity' => $activity, 'class_id' => $class_id]) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i data-feather="download" style="width: 16px; vertical-align: middle;"></i>
                                        Export ke Excel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                        <div class="alert alert-info">
                            <i data-feather="info" style="width: 16px; vertical-align: middle;"></i>
                            Belum ada data siswa untuk kelas ini.
                        </div>
                    @else
                        <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                            <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                                   id="datatable">
                                <thead class="table-success">
                                <tr>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                    <th class="text-start">Nama</th>
                                    <th class="text-start">NIS</th>
                                    <th class="text-start">Jadwal</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Status</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-start">{{ $student->name }}</td>
                                        <td class="text-start">{{ $student->nis }}</td>
                                        <td class="text-start">{{ $student->schedule }}</td>
                                        <td class="text-center">
                                            @if($student->status == 'Selesai')
                                                <span class="badge badge-success rounded-pill p-2">Selesai</span>
                                            @else
                                                <span class="badge badge-danger rounded-pill p-2">Belum Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($student->period_id)
                                                @php
                                                    // Deteksi route berdasarkan activity name
                                                    $activityUpper = strtoupper($activity);
                                                    $isSCR = str_contains($activityUpper, 'SCR') || str_contains($activityUpper, 'SCREENING');
                                                    $routeName = $isSCR ? 'medical-checkup.form' : 'dcu.form';
                                                    $routeParams = ['student' => $student->id, 'period' => $student->period_id];
                                                @endphp
                                                <a class="btn btn-info btn-sm" 
                                                   href="{{ route($routeName, $routeParams) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="16" height="16" viewBox="0 0 24 24"
                                                         class="icon feather feather-eye" 
                                                         style="width: 16px;vertical-align: middle;">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                            @else
                                                <span class="text-muted">Belum ada data</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <script>
        $(document).ready(function () {
            let table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[1, 'asc']],
                "columnDefs": [
                    {"orderable": false, "targets": [0, 5]}
                ]
            });

            table.on('draw.dt', function () {
                let PageInfo = $('#datatable').DataTable().page.info();
                table.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        });
    </script>
@endsection