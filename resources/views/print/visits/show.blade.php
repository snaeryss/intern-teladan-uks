@extends('layouts.main')

<x-datatables.required />

@section('content')
    @include('components.content-title', [
        'active' => $title,
        'menus' => ['Cetak Dokumen', 'Kunjungan', 'Periode', $title],
    ])

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-0">{{ $title }}</h4>
                                <p class="text-muted mb-0 mt-2">
                                    <strong>Periode:</strong> {{ $periodeName }} |
                                    <strong>Total Kunjungan:</strong> {{ $visits->count() }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('print-documents.visits') }}" class="btn btn-light btn-sm me-2">
                                    <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                                    Kembali
                                </a>
                                <a href="{{ route('print-documents.visits.export', ['month' => $month, 'year' => $year]) }}"
                                    class="btn btn-success btn-sm">
                                    <i data-feather="download" style="width: 16px; vertical-align: middle;"></i>
                                    Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                            <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                                id="datatable">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center" style="width: 5%; white-space: nowrap;">No</th>
                                        <th class="text-start">Tanggal</th>
                                        <th class="text-start">Hari</th>
                                        <th class="text-start">Nama Siswa</th>
                                        <th class="text-start">NIS</th>
                                        <th class="text-center">Jam Datang</th>
                                        <th class="text-center">Jam Pulang</th>
                                        <th class="text-start">Keluhan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($visits as $visit)
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-start">
                                                {{ \Carbon\Carbon::parse($visit->date)->format('d/m/Y') }}</td>
                                            <td class="text-start">{{ $visit->day ?? '-' }}</td>
                                            <td class="text-start">{{ $visit->student->name ?? '-' }}</td>
                                            <td class="text-start">{{ $visit->student->nis ?? '-' }}</td>
                                            <td class="text-center">{{ $visit->arrival_time ?? '-' }}</td>
                                            <td class="text-center">{{ $visit->departure_time ?? '-' }}</td>
                                            <td class="text-start">{{ $visit->complaint ?? '-' }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <script>
        $(document).ready(function() {
            let table = $("#datatable").DataTable({
                "pageLength": 50,
                "lengthMenu": [25, 50, 100, 200],
                "order": [
                    [1, 'asc'],
                    [6, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0]
                }],
                "language": {
                    "emptyTable": "Tidak ada data kunjungan pada periode ini"
                }
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
