@php
    use Illuminate\Support\Js;
@endphp

@extends("layouts.main")

<x-sweet-alert2.required/>

@section('content')
    @include('components.content-title', ['active' => 'Lokasi Pemeriksaan', 'menus' => ['Lokasi Pemeriksaan']])
    @include('locations.modal.create')
    @include('locations.modal.edit')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <button class="btn btn-primary"
                            style="float:right;"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-create">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16" viewBox="0 0 24 24"
                             class="icon feather feather-plus-circle" style="width: 16px;vertical-align: middle;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Tambah Lokasi
                    </button>
                    <h4 class="card-title mb-0">Daftar Lokasi Pemeriksaan</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('locations') }}" id="per-page-form">
                                <div class="d-flex align-items-center">
                                    <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="document.getElementById('per-page-form').submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    <span class="ms-2">entries per page</span>
                                </div>
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('locations') }}" id="search-form">
                                <div class="d-flex align-items-center justify-content-end">
                                    <label class="me-2">Search:</label>
                                    <input type="text" name="search" class="form-control form-control-sm" style="width: 200px;" value="{{ request('search') }}" placeholder="Cari lokasi...">
                                </div>
                                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive add-project custom-scrollbar">
                        <table class="table card-table table-bordered table-striped text-nowrap">
                            <thead>
                            <tr class="table-success">
                                <th class="text-center" style="width: 10px;">
                                    <a href="{{ route('locations', array_merge(request()->all(), ['sort_by' => 'id', 'sort_order' => request('sort_by') == 'id' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        No
                                        @if(request('sort_by') == 'id')
                                            <i class="fa fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('locations', array_merge(request()->all(), ['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Nama Lokasi
                                        @if(request('sort_by') == 'name')
                                            <i class="fa fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center" style="width: 100px;">
                                    <a href="{{ route('locations', array_merge(request()->all(), ['sort_by' => 'is_active', 'sort_order' => request('sort_by') == 'is_active' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Status
                                        @if(request('sort_by') == 'is_active')
                                            <i class="fa fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($locations as $index => $location)
                                <tr>
                                    <td class="text-center"><b>{{ $locations->firstItem() + $index }}.</b></td>
                                    <td>{{ $location->name }}</td>
                                    <td class="text-center">
                                        @if($location->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm"
                                                onclick="editData({{ Js::from($location) }})"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16" viewBox="0 0 24 24"
                                                 class="icon feather feather-edit" style="width: 16px;vertical-align: middle;">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">
                                        :: tidak ada data ::
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Showing {{ $locations->firstItem() ?? 0 }} to {{ $locations->lastItem() ?? 0 }} of {{ $locations->total() }} entries
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $locations->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
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
        const editData = (data) => {
            $('#name_edit').val(data.name);
            $('#is_active_edit').val(data.is_active ? '1' : '0');
            $('#form-edit').attr('action', '{{ route('locations.update', ':id') }}'.replace(':id', data.id));
        };
        
        document.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                document.getElementById('search-form').submit();
            }, 500);
        });
    </script>
@endsection