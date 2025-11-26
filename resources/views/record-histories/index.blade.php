@extends('layouts.main')

<x-datatables.required />

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => ['Riwayat Pemeriksaan', $title]])

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            {{--  --}}
                            @if (auth()->user()->hasRole(['SuperVisor', 'Doktor']))
                                <a href="{{ route('record-histories.index', ['type' => 'scr']) }}"
                                    class="btn btn-sm {{ $type === 'scr' ? 'btn-success' : 'btn-outline-success' }}">
                                    SCR - Semua
                                </a>
                            @endif

                            {{-- Tombol MCU - hanya untuk SuperVisor & Doktor --}}
                            @if (auth()->user()->hasRole(['SuperVisor', 'Doktor']))
                                <a href="{{ route('record-histories.index', ['type' => 'mcu']) }}"
                                    class="btn btn-sm {{ $type === 'mcu' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    MCU - Semua
                                </a>
                            @endif

                            {{-- Tombol DCU - hanya untuk SuperVisor & Doktor Gigi --}}
                            @if (auth()->user()->hasRole(['SuperVisor', 'Doktor Gigi']))
                                <a href="{{ route('record-histories.index', ['type' => 'dcu']) }}"
                                    class="btn btn-sm {{ $type === 'dcu' ? 'btn-info' : 'btn-outline-info' }}">
                                    DCU - Semua
                                </a>
                            @endif
                        </div>


                        <div class="table-responsive dt-bootstrap5 custom-scrollbar mb-0">
                            <table class="table table-bordered table-striped table-vcenter text-nowrap mb-0" id="datatable">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                        <th class="text-start">Nama</th>
                                        <th class="text-start">NIS</th>
                                        <th class="text-start">Kelas</th>
                                        <th class="text-start">Periode</th>
                                        <th class="text-start">Selesai Pada</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($histories as $index => $item)
                                        <tr>
                                            <td class="text-center"></td>

                                            <td class="text-start">{{ $item['student_name'] }}</td>
                                            <td class="text-start">{{ $item['student_nis'] }}</td>
                                            <td class="text-start">{{ $item['class'] }}</td>
                                            <td class="text-start">{{ $item['period'] }}</td>
                                            <td class="text-start">{{ $item['finished_at'] }}</td>

                                            <td class="text-center">
                                                @if ($item['type'] === 'DCU')
                                                    <a href="{{ route('dcu.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                        class="btn btn-success btn-sm" title="Lihat Detail">
                                                        <i class="fa fa-file"></i> Lihat
                                                    </a>

                                                    {{-- $item['period']->is_active ->> $item['period_is_active'] --}}
                                                    @if ($item['period_is_active'] ?? false)
                                                        <a href="{{ route('dcu.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                            class="btn btn-primary btn-sm" title="Ubah Data">
                                                            <i class="fa fa-edit"></i> Ubah Data
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('medical-checkup.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                        class="btn btn-success btn-sm" title="Lihat Detail">
                                                        <i class="fa fa-file"></i> Lihat
                                                    </a>

                                                    {{-- $item['period']->is_active ->>> $item['period_is_active'] --}}
                                                    @if ($item['period_is_active'] ?? false)
                                                        <a href="{{ route('medical-checkup.form', ['student' => $item['student_id'], 'period' => $item['period_id']]) }}"
                                                            class="btn btn-primary btn-sm" title="Ubah Data">
                                                            <i class="fa fa-edit"></i> Ubah Data
                                                        </a>
                                                    @endif
                                                @endif
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
            if (typeof toastr !== 'undefined') {
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.clear();
            }

            if (typeof Swal !== 'undefined') {
                window.Swal = {
                    fire: function() {
                        return Promise.resolve();
                    },
                    close: function() {},
                    mixin: function() {
                        return this;
                    }
                };
            }

            window.alert = function() {};

            let table = $("#datatable").DataTable({
                "pageLength": 50,
                "lengthMenu": [10, 25, 50, 100],
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 6]
                }]
            });

            function updateRowNumbers() {
                let PageInfo = table.page.info();
                table.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            }

            table.on('draw.dt', function() {
                updateRowNumbers();
            });

            updateRowNumbers();
        });
    </script>
@endsection
