@extends('layouts.main')

@push('styles-css')
    <x-select2-required />
@endpush

@section('content')
    @include('components.content-title', [
        'active' => isset($visit) ? 'Edit Kunjungan' : 'Tambah Kunjungan',
        'menus' => ['UKS', 'Data Kunjungan', isset($visit) ? 'Edit' : 'Tambah'],
    ])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ isset($visit) ? 'Edit' : 'Tambah' }} Kunjungan UKS</h4>
                        <a href="{{ route('visits.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 16px; vertical-align: middle;"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i data-feather="alert-circle" style="width: 16px; vertical-align: middle;"></i>
                            <strong>Terdapat kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ isset($visit) ? route('visits.update', $visit->id) : route('visits.store') }}" method="POST">
                        @csrf
                        @if(isset($visit))
                            @method('PUT')
                        @endif
                        
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Data Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                @include('visits.partials._visitor-data')
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Data Pemeriksaan</h6>
                            </div>
                            <div class="card-body">
                                @include('visits.partials._examination')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <a href="{{ route('visits.index') }}" class="btn btn-light">
                                    <i data-feather="x" style="width: 16px; vertical-align: middle;"></i> 
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i data-feather="save" style="width: 16px; vertical-align: middle;"></i> 
                                    {{ isset($visit) ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stack-js')
    <script>
        $(document).ready(function() {
            @if(!isset($visit))
            const today = new Date().toISOString().split('T')[0];
            $('#date').val(today);
            updateDayOfWeek(today);
            @else
            updateDayOfWeek($('#date').val());
            @endif

            $('#date').on('change', function() {
                updateDayOfWeek($(this).val());
            });

            $('#student_id').select2({
                placeholder: '— Cari berdasarkan NIS atau Nama Siswa —',
                allowClear: true,
                ajax: {
                    url: "{{ route('student.live-search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            level: 'all'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    class: item.class
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#student_id').on('select2:select', function(e) {
                const data = e.params.data;
                $('#student_class').val(data.class || '-');
            });

            $('#student_id').on('select2:clear', function() {
                $('#student_class').val('');
            });
        });

        function updateDayOfWeek(dateString) {
            if (!dateString) return;
            
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const date = new Date(dateString);
            const dayName = days[date.getDay()];
            
            $('#day').val(dayName);
        }
    </script>
@endpush