@php
    use App\Enums\CheckUpTypeEnum;
@endphp

@extends("layouts.main")

<x-datatables.required/>

@push('styles-css')
    <x-select2-required />
    <x-sweet-alert2.required/>
@endpush

@section('content')
    @include('components.content-title', ['active' => 'Periode Kegiatan', 'menus' => ['Periode Kegiatan', 'Detail']])
    @include('periods.modal.form')
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                Periode Kegiatan - {{ $academicYear->year_start }}/{{ $academicYear->year_end }}
                            </h4>
                        </div>
                        <div>
                            <a href="{{ route('academic-year') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                            Kembali
                        </a>
                        <button class="btn btn-primary btn-sm"
                                type="button"
                                onclick="openCreateModal()"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-form">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 style="width: 16px;vertical-align: middle;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Tambah Periode
                        </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap"
                               id="datatable">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">No</th>
                                <th class="text-start">Kegiatan</th>
                                <th class="text-start">Periode</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Status</th>
                                <th class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($periods as $period)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-start">
                                        {{ CheckUpTypeEnum::from($period->name)->label() }}
                                    </td>
                                    <td class="text-start">{{ $period->month }} {{ $period->year }}</td>
                                    <td class="text-center">
                                        @if($period->is_active)
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
                                        <form action="{{ route('periods.toggle-status', $period->id) }}" 
                                              method="POST" 
                                              style="display: inline-block;"
                                              onsubmit="return confirm('Yakin ingin mengubah status periode ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $period->is_active ? 'warning' : 'success' }} btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     style="width: 16px;vertical-align: middle;">
                                                    <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                    <circle cx="{{ $period->is_active ? '16' : '8' }}" cy="12" r="3"></circle>
                                                </svg>
                                                {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        
                                        <button class="btn btn-info btn-sm"
                                                type="button"
                                                onclick="editData({{ json_encode($period) }})"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-form">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 style="width: 16px;vertical-align: middle;">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
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
            // Initialize Select2
            $('.js-example-basic-single').select2({
                dropdownParent: $('#modal-form')
            });

            // Initialize DataTable
            let table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[1, 'asc']],
                "columnDefs": [
                    {"orderable": false, "targets": [0, 4]}
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
        });
        
        let isEditMode = false;
        
        const openCreateModal = () => {
            isEditMode = false;
            $('#modal-form-title').text('Tambah Periode');
            $('#form-period').attr('action', '{{ route('periods.store', $academicYear->id) }}');
            $('#name').val('').trigger('change');
            $('#month').val('').trigger('change');
            $('#year').val('');
        };

        const editData = (data) => {
            isEditMode = true;
            $('#modal-form-title').text('Edit Periode');
            $('#form-period').attr('action', '{{ route('periods.update', ':id') }}'.replace(':id', data.id));
            $('#name').val(data.name).trigger('change');
            $('#month').val(data.month).trigger('change');
            $('#year').val(data.year);
        };
    </script>
@endsection