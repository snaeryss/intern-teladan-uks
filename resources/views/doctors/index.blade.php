{{-- resources/views/doctors/index.blade.php - CLEAN VERSION --}}

@extends("layouts.main")

<x-datatables.required/>

@push('styles-css')
    <x-sweet-alert2.required/>
@endpush

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => [$title]])
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                        @hasrole('SuperVisor')
                        <a href="{{ route('doctor.create') }}" class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 style="width: 16px;vertical-align: middle;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Tambah Dokter
                        </a>
                        @endhasrole
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                               id="datatable">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                <th class="text-start">Nama</th>
                                <th class="text-start">Username</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Status</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Akun</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-start">{{ $doctor->name }}</td>
                                    <td class="text-start">
                                        @if($doctor->account)
                                            <span>
                                                {{ $doctor->account->user->username }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($doctor->is_active)
                                            <span class="badge badge-success rounded-pill p-2">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-secondary rounded-pill p-2">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($doctor->account)
                                            <span class="badge badge-info rounded-pill p-2">
                                                <i class="fa fa-check"></i> Ada
                                            </span>
                                        @else
                                            <span class="badge badge-danger rounded-pill p-2">
                                                <i class="fa fa-times"></i> Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @hasrole('SuperVisor')
                                        <form action="{{ route('doctor.destroy', $doctor) }}" 
                                              method="POST" 
                                              style="display: inline-block;"
                                              class="form-toggle-status"
                                              data-doctor-name="{{ $doctor->name }}"
                                              data-current-status="{{ $doctor->is_active ? 'active' : 'inactive' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-{{ $doctor->is_active ? 'warning' : 'success' }} btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     style="width: 16px;vertical-align: middle;">
                                                    <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                    <circle cx="{{ $doctor->is_active ? '16' : '8' }}" cy="12" r="3"></circle>
                                                </svg>
                                                {{ $doctor->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        @endhasrole
                                        
                                        <a href="{{ route('doctor.detail', $doctor) }}" 
                                           class="btn btn-info btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 style="width: 16px;vertical-align: middle;">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
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
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    
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

            $('.form-toggle-status').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const doctorName = form.data('doctor-name');
                const currentStatus = form.data('current-status');
                const action = currentStatus === 'active' ? 'menonaktifkan' : 'mengaktifkan';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    html: `Yakin ingin <strong>${action}</strong> dokter<br><strong>${doctorName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: currentStatus === 'active' ? '#ffc107' : '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Ya, ${action}!`,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });
        });
    </script>
@endsection