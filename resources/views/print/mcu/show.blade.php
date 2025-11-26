@extends('layouts.main')

<x-datatables.required />

@section('yield-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Cetak Dokumen', 'MCU', $title]])
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $title }}</h4>
                            <p class="text-muted mb-0 mt-2">
                                <strong>Siswa:</strong> {{ $selectedStudent->name }} ({{ $selectedStudent->nis }}) | 
                                <strong>Jenjang:</strong> {{ $selectedLevel }}
                            </p>
                        </div>
                        <a href="{{ route('print-documents.mcu') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap" id="datatable">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                    <th class="text-start">Nama Siswa</th>
                                    <th class="text-start">Nomor Siswa (NIS)</th>
                                    <th class="text-start">Pelaksanaan NCU</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-start">{{ $record->student->name }}</td>
                                        <td class="text-start">{{ $record->student->nis }}</td>
                                        <td class="text-start">
                                            @if($record->period)
                                                {{ $record->period->month }} {{ $record->period->year }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm" disabled>
                                                <i data-feather="eye" style="width: 16px; vertical-align: middle;"></i>
                                                Lihat
                                            </button>
                                            {{-- Aktifkan setelah route dibuat:
                                            <a class="btn btn-success btn-sm" href="{{ route('print-documents.mcu.detail', $record->id) }}">
                                                <i data-feather="eye" style="width: 16px; vertical-align: middle;"></i>
                                                Lihat
                                            </a>
                                            --}}
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
@endsection

@section('yield-js')
    <script>
        $(document).ready(function() {
            let table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[3, 'desc']], 
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 4]
                }],
                "language": {
                    "emptyTable": "No data available in table"
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
