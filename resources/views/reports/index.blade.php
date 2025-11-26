@extends("layouts.main")

<x-datatables.required/>

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Laporan', 'List Tahun Akademik']])

    
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
                                <th class="text-start" style="width: 1%; white-space: nowrap;">No</th>
                                <th class="text-start">Tahun Akademik</th>
                                <th class="text-start">Angkatan</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Jumlah Kegiatan</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $reports)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-start">{{ $reports->year_start }}/{{ $reports->year_end }}</td>
                                    <td class="text-start">{{ $reports->year_start }}</td>
                                    <td class="text-center">{{ $reports->periods_count }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('reports.detail', $reports->id) }}">
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
                "pageLength": 10, 
                "lengthMenu": [10, 25, 50, 100], 
                "order": [[1, 'desc']], 
                "columnDefs": [
                    {"orderable": false, "targets": [0, 4]}
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