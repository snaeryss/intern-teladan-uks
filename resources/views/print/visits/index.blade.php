@extends('layouts.main')

<x-datatables.required />

@section('content')
    @include('components.content-title', [
        'active' => $title,
        'menus' => ['Cetak Dokumen', 'Kunjungan', $title]
    ])

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            Pilih periode untuk melihat laporan kunjungan bulanan
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                            <table class="table card-table table-bordered table-striped table-vcenter text-nowrap" id="datatable">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center" style="width: 5%; white-space: nowrap;">No</th>
                                        <th class="text-start">Periode</th>
                                        <th class="text-center">Jumlah Kunjungan</th>
                                        <th class="text-center" style="width: 15%; white-space: nowrap;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monthlyVisits as $visit)
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-start">
                                                {{ \Carbon\Carbon::parse($visit->date)->translatedFormat('F Y') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $visit->total }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('print-documents.visits.show', ['month' => \Carbon\Carbon::parse($visit->date)->format('m'), 'year' => \Carbon\Carbon::parse($visit->date)->format('Y')]) }}" 
                                                   class="btn btn-success btn-sm">
                                                    <i data-feather="eye" style="width: 16px; vertical-align: middle;"></i>
                                                    Lihat
                                                </a>
                                                <a href="{{ route('print-documents.visits.export', ['month' => \Carbon\Carbon::parse($visit->date)->format('m'), 'year' => \Carbon\Carbon::parse($visit->date)->format('Y')]) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i data-feather="printer" style="width: 16px; vertical-align: middle;"></i>
                                                    Cetak
                                                </a>
                                            </td>
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
                "pageLength": 12,
                "lengthMenu": [12, 25, 50, 100],
                "order": [[1, 'desc']],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 3]
                }],
                "language": {
                    "emptyTable": "Belum ada data kunjungan"
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
