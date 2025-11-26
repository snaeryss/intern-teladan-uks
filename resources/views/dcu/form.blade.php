@extends('layouts.main')

@push('styles')
    @include('components.datatables.required')
@endpush

@section('content')
    @include('components.content-title', [
        'active' => 'Dental Check Up ',
        'menus' => ['Daftar Tunggu', 'Dental Check Up '],
    ])

    <div class="container-fluid">
        @include('partials._student-identity', ['student' => $student])

        <div class="card">
            <div class="card-header">
                <h5>Formulir Dental Check Up </h5>
                <p class="f-m-light mt-1">Jenjang: {{ $levelName }}</p>
            </div>

            <div class="card-body basic-wizard important-validation mt-4">
            <div class="stepper-horizontal custom-scrollbar" id="dcu-stepper">
                @if (in_array($level, ['dctk', 'sd', 'smp', 'sma']))
                    <div class="step active" data-step="0" style="cursor: pointer;">
                        <div class="step-circle"><span>1</span></div>
                        <div class="step-title">Ondotogram</div>
                        <div class="step-bar-left"></div>
                        <div class="step-bar-right"></div>
                    </div>
                    <div class="step" data-step="1" style="cursor: pointer;">
                        <div class="step-circle"><span>2</span></div>
                        <div class="step-title">Pemeriksaan Umum</div>
                        <div class="step-bar-left"></div>
                        <div class="step-bar-right"></div>
                    </div>
                    <div class="step" data-step="2" style="cursor: pointer;">
                        <div class="step-circle"><span>3</span></div>
                        <div class="step-title">Lainnya</div>
                        <div class="step-bar-left"></div>
                        <div class="step-bar-right"></div>
                    </div>
                @endif
            </div>

                <div class="form-container mt-5">
                    <form id="dcu-form" method="POST" action="#">
                        @csrf
                        <input type="hidden" id="current_student_id" value="{{ $student->id }}">
                        <input type="hidden" id="current_period_id" value="{{ $period->id }}">
                        <input type="hidden" id="current_location_id" value="{{ $location->id ?? 1 }}">
                        <input type="hidden" id="student_gender" value="{{ $student->sex?->value ?? '-' }}">
                        <input type="hidden" id="student_birth_date" value="{{ $student->date_birth->format('Y-m-d') }}">
                        
                        @if ($level == 'sd')
                            @include('dcu.partials._form-wrapper-sd')
                        @elseif ($level == 'dctk')
                            @include('dcu.partials._form-wrapper-dctk')
                        @elseif ($level == 'smp')
                            @include('dcu.partials._form-wrapper-smp')
                        @elseif ($level == 'sma')
                            @include('dcu.partials._form-wrapper-sma')
                        @endif
                    </form>
                </div>

                <div class="wizard-footer d-flex gap-3 justify-content-end mt-5">
                    <button type="button" class="btn btn-light" id="prevBtn" onclick="nextPrev(-1)"> <i
                            class="fa-solid fa-circle-left"></i></i> Sebelumnya</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)"> <i
                            class="fa-solid fa-circle-right"></i> <span id="nextBtnText">Selanjutnya</span></button>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    @include('components.datatables.handler')

    <script src="{{ asset('js/form-wizard/dcu/form-wizard.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/form-wizard/dcu/form-submit.js') }}?v={{ time() }}"></script>

    <script src="{{ asset('js/dcu/ondotogram.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/dcu/pemeriksaan-umum.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/dcu/evaluasi.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/evaluator-doctor.js') }}?v={{ time() }}"></script>

    <script>
        const metaUserId = document.createElement('meta');
        metaUserId.name = 'user-id';
        metaUserId.content = '{{ auth()->id() }}';
        document.head.appendChild(metaUserId);

        const metaUserRole = document.createElement('meta');
        metaUserRole.name = 'user-can-save';
        metaUserRole.content = '{{ auth()->user()->hasAnyRole(["Doktor", "Doktor Gigi"]) ? "true" : "false" }}';
        document.head.appendChild(metaUserRole);

        document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const steppers = document.querySelectorAll('#dcu-stepper .step[data-step]');
        
        steppers.forEach(function(stepper) {
            stepper.addEventListener('click', function() {
                const targetStep = parseInt(this.getAttribute('data-step'));
                
                console.log('Clicked step:', targetStep, '(current:', currentTab, ')');

                currentTab = targetStep;
                showTab(currentTab);
            });

            stepper.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.opacity = '0.7';
                }
            });
            
            stepper.addEventListener('mouseleave', function() {
                this.style.opacity = '1';
            });
        });
        
        console.log('DCU Stepper nodes are now clickable!');
    }, 1000);
});
    </script>
@endsection