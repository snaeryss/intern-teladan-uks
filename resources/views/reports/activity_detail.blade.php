@extends("layouts.main")

<x-datatables.required/>

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Laporan', 'Detail Kegiatan', $title]])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            {{ $title }} - Tahun Akademik {{ $academicYear->year_start }}/{{ $academicYear->year_end }}
                        </h4>
                        <a href="{{ route('reports.detail', $academicYear->id) }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                               id="datatable">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                <th class="text-start">Kelas</th>
                                <th class="text-start">Jumlah</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($classes as $class)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-start">{{ $class->name }}</td>
                                    <td class="text-start">{{ $class->student_count }} Siswa</td>
                                    <td class="text-center">
                                        <a class="btn btn-success btn-sm" href="{{ route('reports.student.list', ['id' => $academicYear->id, 'activity' => $activity, 'class_id' => $class->id]) }}">
                                            <i data-feather="eye" style="width: 16px; vertical-align: middle;"></i>
                                            Lihat
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
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[1, 'asc']], 
                "columnDefs": [
                    {"orderable": false, "targets": [0, 3]}
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