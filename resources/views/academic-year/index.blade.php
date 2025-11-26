@php
    use App\Enums\AcademicYearStatusEnum;
    use Illuminate\Support\Js;
@endphp

@extends("layouts.main")

<x-sweet-alert2.required/>

@section('content')
    @include('components.content-title', ['active' => $title, 'menus' => [$title]])
    @include('academic-year.modal.create')
    @include('academic-year.modal.edit')
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
                        Buat Baru
                    </button>
                    <h4 class="card-title mb-0">
                        {{ $title }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive add-project custom-scrollbar">
                        <table class="table card-table table-bordered table-striped">
                            <thead>
                            <tr class="table-success">
                                <th class="text-center" style="width: 10px;">No</th>
                                <th>Tahun Mulai</th>
                                <th>Tahun Selesai</th>
                                <th style="width: 60px;">Status</th>
                                <th style="width: 250px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($academicYears as $index => $value)
                                <tr>
                                    <td class="text-center">
                                        <b>{{ $index + 1 }}.</b>
                                    </td>
                                    <td>
                                        <a class="text-inherit" href="#">
                                            {{ $value->year_start }}
                                        </a>
                                    </td>
                                    <td>{{ $value->year_end }}</td>
                                    <td>
                                        @if($value->is_active === AcademicYearStatusEnum::ACTIVE)
                                            <span class="badge badge-success rounded-pill p-2">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-secondary rounded-pill p-2">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-info btn-sm"
                                                type="button"
                                                onclick="editData({{ Js::from($value) }})"
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

                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('periods.show', $value->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-arrow-right-circle"
                                                 style="width: 16px;vertical-align: middle;">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 16 16 12 12 8"></polyline>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                            Detail
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
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    <script>
        const editData = (data) => {
            $('#year_start').val(data.year_start);
            $('#year_end').val(data.year_end);
            $('#is_active').val(data.is_active).trigger('change');
            $('#form-edit').attr('action', '{{ route('academic-year.update', ':id') }}'.replace(':id', data.id));
        };
    </script>
@endsection
