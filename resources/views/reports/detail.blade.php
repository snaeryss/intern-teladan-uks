@extends('layouts.main')

<x-datatables.required />

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Laporan', $title]])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            {{ $title }} Tahun Akademik {{ $academicYear->year_start }}/{{ $academicYear->year_end }}
                        </h4>
                        <a href="{{ route('reports') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($activities->isEmpty())
                        <div class="alert alert-info">
                            <i data-feather="info" style="width: 16px; vertical-align: middle;"></i>
                            Belum ada kegiatan untuk tahun akademik ini.
                        </div>
                    @else
                        <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                            <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                                id="datatable">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-start" style="width: 1%; white-space: nowrap;">No</th>
                                        <th class="text-start">Kegiatan</th>
                                        <th class="text-start">Periode</th>
                                        <th class="text-center" style="width: 1%; white-space: nowrap;">Jumlah Peserta</th>
                                        <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activities as $activity)
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-start">{{ $activity->name }}</td>
                                            <td class="text-start">{{ $activity->periode }}</td>
                                            <td class="text-center">{{ $activity->participants }}</td>
                                            <td class="text-center">
                                               <a class="btn btn-primary btn-sm" 
                                                  href="{{ route('reports.activity.detail', ['id' => $academicYear->id, 'activity' => $activity->original_name]) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <script>
        $(document).ready(function() {
            let table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50,100],
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 4]
                }]
            });

            table.on('draw.dt', function() {
                let PageInfo = $('#datatable').DataTable().page.info();
                table.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        });
    </script>
@endsection