@extends('layouts.main')

@push('styles-css')
    <x-select2-required />
    <x-sweet-alert2.required />
@endpush

@section('content')
    @include('components.content-title', [
        'active' => $title,
        'menus' => ['Cetak Dokumen', 'MCU', $title],
    ])

    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" method="GET" action="{{ route('print-documents.mcu.show') }}" novalidate="">
                        @csrf
                        
                        <div class="col-12">
                            <label class="form-label" for="level">Jenjang</label>
                            <select class="form-select js-example-basic-single" id="level" name="level" required="">
                                <option disabled selected value="">— Pilih Jenjang —</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['code'] }}">{{ $level['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback-custom">Silakan pilih jenjang.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="student_id">Siswa</label>
                            <select class="form-select" id="student_id" name="student_id" required="" disabled>
                                <option disabled selected value="">— Pilih Jenjang Terlebih Dahulu —</option>
                            </select>
                            <div class="invalid-feedback-custom">Silakan pilih siswa dari daftar.</div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-search"></i>
                                Cek
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
            $('#level').select2();

            $('#student_id').select2({
                placeholder: '— Cari berdasarkan NIS atau Nama Siswa —',
                ajax: {
                    url: "{{ route('student.live-search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            level: $('#level').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });

            $('#level').on('change', function() {
                const levelValue = $(this).val();
                
                if (levelValue) {
                    $('#student_id').prop('disabled', false);
                    $('#student_id').empty().append('<option disabled selected value="">— Cari & Pilih Siswa —</option>');
                } else {
                    $('#student_id').prop('disabled', true);
                    $('#student_id').empty().append('<option disabled selected value="">— Pilih Jenjang Terlebih Dahulu —</option>');
                }

                $('#student_id').val(null).trigger('change');
            });

            function validateForm() {
                let isValid = true;

                $('.form-control, .form-select').removeClass('is-invalid');
                $('.select2-selection').removeClass('is-invalid');

                const fieldsToValidate = [
                    { id: '#level' },
                    { id: '#student_id' }
                ];

                fieldsToValidate.forEach(function(field) {
                    const element = $(field.id);
                    if (!element.val()) {
                        isValid = false;
                        element.addClass('is-invalid');
                        if (element.hasClass('select2-hidden-accessible')) {
                           element.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                        }
                    }
                });

                return isValid;
            }

            $('form').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    Swal.fire('Form Belum Lengkap', 'Mohon isi semua field yang ditandai merah!', 'warning');
                    return false;
                }
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