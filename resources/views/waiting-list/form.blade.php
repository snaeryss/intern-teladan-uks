@extends('layouts.main')

@push('styles-css')
    <x-select2-required />
    <x-sweet-alert2.required />
@endpush

@section('content')
    @include('components.content-title', [
        'active' => $title,
        'menus' => ['Daftar Tunggu', strtoupper($level), $title],
    ])

    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Formulir Daftar Tunggu UKS - {{ strtoupper($level) }} Teladan</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="validation-form" novalidate="">
                        @csrf
                        <input type="hidden" name="level" value="{{ $level }}">

                        <div class="col-12">
                            <label class="form-label" for="student_id">Siswa</label>
                            <select class="form-select" id="student_id" name="student_id" required="">
                                <option></option>
                                {{-- <option disabled selected value="">— Cari & Pilih Siswa —</option> --revisi}}
                                {{-- @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }}</option>
                                @endforeach --}}
                            </select>
                            <div class="invalid-feedback-custom">Silakan pilih siswa dari daftar.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                            <input class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" type="date"
                                value="{{ date('Y-m-d') }}" required="">
                            <div class="invalid-feedback-custom">Tanggal wajib diisi.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="lokasi">Lokasi</label>
                            <select class="form-select js-example-basic-single" id="location_id" name="location_id"
                                required="">
                                <option disabled selected value="">— Pilih Lokasi —</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback-custom">Lokasi wajib diisi.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="kegiatan">Kegiatan</label>
                            <select class="form-select js-example-basic-single" id="period_id" name="period_id"
                                required="">
                                <option selected disabled value="">— Pilih Kegiatan —</option>
                                @foreach ($periods as $period)
                                    <option value="{{ $period->id }}">{{ $period->display_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback-custom">Silakan pilih kegiatan.</div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-success" type="button" id="next-button">
                                <i class="fa-solid fa-circle-right"></i>
                                Proses Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stack-js')
    <x-sweet-alert2.handler />
    <script>
        $(document).ready(function() {
            $('#location_id, #period_id').select2();

            $('#student_id').select2({
                placeholder: '— Cari berdasarkan NIS atau Nama Siswa —',
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('student.live-search') }}",
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    data: function(params) {
                        return {
                            term: params.term,
                            level: "{{ $level }}",
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });

            function validateForm() {
                let isValid = true;

                $('.form-control, .form-select').removeClass('is-invalid');
                $('.select2-selection').removeClass('is-invalid');

                const fieldsToValidate = [{
                        id: '#student_id'
                    },
                    {
                        id: '#tanggal_pemeriksaan'
                    },
                    {
                        id: '#location_id'
                    },
                    {
                        id: '#period_id'
                    }
                ];

                fieldsToValidate.forEach(function(field) {
                    const element = $(field.id);
                    if (!element.val()) {
                        isValid = false;
                        element.addClass('is-invalid');
                        if (element.hasClass('select2-hidden-accessible')) {
                            element.next('.select2-container').find('.select2-selection').addClass(
                                'is-invalid');
                        }
                    }
                });

                return isValid;
            }

            $('#next-button').on('click', function(e) {
                // console.log('Next button was clicked!');
                e.preventDefault();

                if (!validateForm()) {
                    Swal.fire('Form Belum Lengkap', 'Mohon isi semua field yang ditandai merah!',
                        'warning');
                    return;
                }

                const studentId = $('#student_id').val();
                const periodId = $('#period_id').val();
                const locationId = $('#location_id').val();
                const checkupDate = $('#tanggal_pemeriksaan').val();

                Swal.fire({
                    title: 'Memproses Data...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: "{{ route('waiting-list.store') }}",
                    type: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        student_id: studentId,
                        period_id: periodId,
                        location_id: locationId,
                        date: checkupDate
                    },
                    success: function(response) {
                        if (response.is_registered) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Siswa Sudah Terdaftar',
                                text: 'Siswa ini sudah terdaftar pada kegiatan ini. Anda akan tetap dialihkan ke formulir.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pendaftaran berhasil, mengalihkan ke formulir...',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, response.is_registered ? 3000 : 1500);
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                            xhr.responseJSON.error :
                            'Terjadi kesalahan saat memproses data.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        })
                    }
                });
            });
            $('.form-control, .form-select').on('change', function() {
                $(this).removeClass('is-invalid');
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
