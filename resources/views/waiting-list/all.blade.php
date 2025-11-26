@extends('layouts.main')

<x-datatables.required />

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Daftar Tunggu', $title]])

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            {{-- Show buttons based on allowed types --}}
                            @if (in_array('scr', $allowedTypes))
                                <a href="{{ route('medical-record.all', ['type' => 'scr']) }}"
                                    class="btn btn-sm {{ $type === 'scr' ? 'btn-success' : 'btn-outline-success' }}">
                                    SCR - Semua
                                </a>
                            @endif

                            @if (in_array('mcu', $allowedTypes))
                                <a href="{{ route('medical-record.all', ['type' => 'mcu']) }}"
                                    class="btn btn-sm {{ $type === 'mcu' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    MCU - Semua
                                </a>
                            @endif

                            @if (in_array('dcu', $allowedTypes))
                                <a href="{{ route('medical-record.all', ['type' => 'dcu']) }}"
                                    class="btn btn-sm {{ $type === 'dcu' ? 'btn-info' : 'btn-outline-info' }}">
                                    DCU - Semua
                                </a>
                            @endif
                        </div>

                        <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                            <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                                id="datatable">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                        <th class="text-start">Nama</th>
                                        <th class="text-start">Kelas</th>
                                        <th class="text-start">Periode</th>
                                        <th class="text-start">Tanggal Daftar</th>
                                        <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($waitingList as $index => $item)
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-start">{{ $item['student_name'] }}</td>
                                            <td class="text-start">{{ $item['class'] }}</td>
                                            <td class="text-start">{{ $item['period'] }}</td>
                                            <td class="text-start">{{ $item['registered_at'] }}</td>
                                            <td class="text-center">
                                                @if ($item['type'] === 'DCU')
                                                    <a href="{{ route('dcu.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-arrow-right-circle"
                                                            style="width: 16px; vertical-align: middle;">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 16 16 12 12 8"></polyline>
                                                            <line x1="8" y1="12" x2="16"
                                                                y2="12"></line>
                                                        </svg>
                                                        Proses
                                                    </a>
                                                @else
                                                    <a href="{{ route('medical-checkup.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-arrow-right-circle"
                                                            style="width: 16px; vertical-align: middle;">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 16 16 12 12 8"></polyline>
                                                            <line x1="8" y1="12" x2="16"
                                                                y2="12"></line>
                                                        </svg>
                                                        Proses
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Empty state handled by DataTable --}}
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
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 5]
                }]
            });

           function updateRowNumbers() {
                let PageInfo = table.page.info();
                table.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            }
            
            table.on('draw.dt', function () {
                updateRowNumbers();
            });
            
            updateRowNumbers();
        });
    </script>
@endsection