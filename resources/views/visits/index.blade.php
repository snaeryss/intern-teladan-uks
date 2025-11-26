@extends('layouts.main')

<x-datatables.required />

@push('styles-css')
    <x-sweet-alert2.required />
@endpush

@section('content')
    @include('components.content-title', [
        'active' => 'Data Kunjungan',
        'menus' => ['UKS', 'Data Kunjungan'],
    ])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Data Kunjungan UKS</h4>
                        <a href="{{ route('visits.create') }}" class="btn btn-primary btn-sm">
                            <i data-feather="plus" style="width: 16px; vertical-align: middle;"></i>
                            Tambah Kunjungan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i data-feather="check-circle" style="width: 16px; vertical-align: middle;"></i> 
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap" id="datatable">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                    <th class="text-start">Tanggal</th>
                                    <th class="text-start">Hari</th>
                                    <th class="text-center">Jam Datang</th>
                                    <th class="text-center">Jam Keluar</th>
                                    <th class="text-start">Nama Siswa</th>
                                    <th class="text-start">Kelas</th>
                                    <th class="text-start">Keluhan</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visits as $visit)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-start">{{ \Carbon\Carbon::parse($visit->date)->format('d/m/Y') }}</td>
                                        <td class="text-start">{{ $visit->day }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($visit->arrival_time)->format('H:i') }}</td>
                                        <td class="text-center">{{ $visit->departure_time ? \Carbon\Carbon::parse($visit->departure_time)->format('H:i') : '-' }}</td>
                                        <td class="text-start">{{ $visit->student->name }}</td>
                                        <td class="text-start">{{ $visit->student->class }}</td>
                                        <td class="text-start">{{ Str::limit($visit->complaint, 50) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('visits.edit', $visit->id) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit">
                                                <i data-feather="edit-2" style="width: 16px; vertical-align: middle;"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm delete-btn" 
                                                    data-id="{{ $visit->id }}" 
                                                    data-name="{{ $visit->student->name }}" 
                                                    title="Hapus">
                                                <i data-feather="trash-2" style="width: 16px; vertical-align: middle;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data kunjungan</td>
                                    </tr>
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
    <x-sweet-alert2.handler />
    <script>
        $(document).ready(function() {
            let table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[1, 'desc']], 
                "columnDefs": [
                    {"orderable": false, "targets": [0, 8]} 
                ]
            });

            table.on('draw.dt', function() {
                let PageInfo = $('#datatable').DataTable().page.info();
                table.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

            $('.delete-btn').on('click', function() {
                const visitId = $(this).data('id');
                const studentName = $(this).data('name');
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Yakin ingin menghapus data kunjungan ${studentName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('<form>', {
                            'method': 'POST',
                            'action': `/visits/${visitId}`
                        });
                        
                        const csrfToken = $('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        });
                        
                        const methodField = $('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        });
                        
                        form.append(csrfToken).append(methodField);
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection