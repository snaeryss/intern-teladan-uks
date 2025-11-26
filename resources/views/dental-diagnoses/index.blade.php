@extends('layouts.main')

<x-datatables.required/>

@push('stack-css')
    <x-sweet-alert2.required/>
@endpush

@section('content')
    @include('components.content-title', ['active' => 'Diagnosis Gigi', 'menus' => ['Master Data', 'Diagnosis Gigi']])
    
    @hasrole('SuperVisor')
        @include('dental-diagnoses.modals.add')
        @include('dental-diagnoses.modals.edit')
    @endhasrole
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    @hasrole('SuperVisor')
                    <button class="btn btn-primary" style="float:right;" type="button" data-bs-toggle="modal" data-bs-target="#modal-create">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="icon feather feather-plus-circle" style="width: 16px;vertical-align: middle;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Buat Baru
                    </button>
                    @endhasrole
                    <h4 class="card-title mb-0">Manajemen Diagnosis Gigi</h4>
                    <p class="text-muted mt-2">Kelola data diagnosis gigi untuk pemeriksaan DCU</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-bootstrap5 custom-scrollbar">
                        <table class="table card-table table-bordered table-striped table-vcenter text-nowrap" id="datatable">
                            <thead class="table-success">
                            <tr>
                                <th class="text-start" style="width: 1%; white-space: nowrap;"></th>No</th>
                                <th class="text-start" style="width: 15%;">Kode</th>
                                <th class="text-start">Deskripsi</th>
                                @hasrole('SuperVisor')
                                <th  class="text-center" style="width: 1%; white-space: nowrap;">Aksi</th>
                                @endhasrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($diagnoses as $diagnosis)
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-start">{{ $diagnosis->code }}</td>
                                    <td class="text-start">{{ $diagnosis->description }}</td>
                                    @hasrole('SuperVisor')
                                    <td class="text-end">
                                        <button class="btn btn-info btn-sm" type="button" onclick="editData({{ $diagnosis->id }}, '{{ $diagnosis->code }}', `{{ addslashes($diagnosis->description) }}`)" data-bs-toggle="modal" data-bs-target="#modal-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="icon feather feather-edit" style="width: 16px;vertical-align: middle;">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" type="button" onclick="deleteData({{ $diagnosis->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="icon feather feather-trash-2" style="width: 16px;vertical-align: middle;">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                    @endhasrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
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
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    <script>
        $(document).ready(function () {
            var table = $("#datatable").DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[1, 'asc']],
                "columnDefs": [
                    {"orderable": false, "targets": [0, 3]}
                ]
            });

            table.on('draw.dt', function () {
                var PageInfo = $('#datatable').DataTable().page.info();
                table.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = '<b>' + (i + 1 + PageInfo.start) + '.</b>';
                });
            });

            @if($errors->any() && old('_method') === 'PUT')
                editData({{ old('id') }}, '{{ old('code') }}', `{{ old('description') }}`);
                $('#modal-edit').modal('show');
            @elseif($errors->any())
                $('#modal-create').modal('show');
            @endif
        });

        function editData(id, code, description) {
            $('#edit_id').val(id);
            $('#edit_code').val(code);
            $('#edit_description').val(description);
            $('#form-edit').attr('action', '{{ route('dental-diagnoses.index') }}/' + id + '/update');
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Hapus Diagnosis?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('dental-diagnoses.index') }}/' + id;

                    var csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    var method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
