@extends('layouts.main')

@section('content')
    @include('components.content-title', [
        'active' => 'Pemeriksaan Screening',
        'menus' => ['Daftar Tunggu', 'Pemeriksaan Screening'],
    ])

    <div class="container-fluid">
        @include('partials._student-identity', ['student' => $student])

        <div class="card">
            <div class="card-header">
                <h5>Formulir Pemeriksaan Screening</h5>
                <p class="f-m-light mt-1">Jenjang: {{ $levelName }}</p>
            </div>

            <div class="card-body basic-wizard important-validation mt-4">
                <div class="stepper-horizontal custom-scrollbar" id="screening-stepper">
                    @if (in_array($level, ['dctk', 'sd']))
                        <div class="step active" data-step="0" style="cursor: pointer;">
                            <div class="step-circle"><span>1</span></div>
                            <div class="step-title">Status Gizi</div>
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
                            <div class="step-title">Gigi & Mulut</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="3" style="cursor: pointer;">
                            <div class="step-circle"><span>4</span></div>
                            <div class="step-title">Penglihatan & Pendengaran</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="4" style="cursor: pointer;">
                            <div class="step-circle"><span>5</span></div>
                            <div class="step-title">Lainnya</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="5" style="cursor: pointer;">
                            <div class="step-circle"><span>6</span></div>
                            <div class="step-title">Evaluasi</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                    @elseif (in_array($level, ['smp', 'sma']))
                        <div class="step active" data-step="0" style="cursor: pointer;">
                            <div class="step-circle"><span>1</span></div>
                            <div class="step-title">Tanda Vital</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="1" style="cursor: pointer;">
                            <div class="step-circle"><span>2</span></div>
                            <div class="step-title">Status Gizi</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="2" style="cursor: pointer;">
                            <div class="step-circle"><span>3</span></div>
                            <div class="step-title">Kebersihan Diri</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="3" style="cursor: pointer;">
                            <div class="step-circle"><span>4</span></div>
                            <div class="step-title">Gigi & Mulut</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="4" style="cursor: pointer;">
                            <div class="step-circle"><span>5</span></div>
                            <div class="step-title">Penglihatan & Pendengaran</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="5" style="cursor: pointer;">
                            <div class="step-circle"><span>6</span></div>
                            <div class="step-title">Lainnya</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                        <div class="step" data-step="6" style="cursor: pointer;">
                            <div class="step-circle"><span>7</span></div>
                            <div class="step-title">Evaluasi</div>
                            <div class="step-bar-left"></div>
                            <div class="step-bar-right"></div>
                        </div>
                    @endif
                </div>
                <div class="form-container mt-5">
                    <form id="screening-form" class="form-bookmark needs-validation" method="POST" novalidate="">
                        @csrf
                        <input type="hidden" id="current_student_id" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" id="current_period_id" name="period_id" value="{{ $period->id }}">
                        <input type="hidden" name="doctor_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="location_id" value="1">
                        <input type="hidden" id="student_gender" value="{{ $student->sex?->value ?? '-' }}">
                        <input type="hidden" id="student_birth_date" value="{{ $student->date_birth->format('Y-m-d') }}">
                        
                        @if ($level == 'sd')
                            @include('screening.partials._form-wrapper-sd')
                        @elseif ($level == 'dctk')
                            @include('screening.partials._form-wrapper-dctk')
                        @elseif ($level == 'smp')
                            @include('screening.partials._form-wrapper-smp')
                        @elseif ($level == 'sma')
                            @include('screening.partials._form-wrapper-sma')
                        @endif
                    </form>
                </div>
                <div class="wizard-footer d-flex gap-3 justify-content-end mt-5">
                    <button type="button" class="btn btn-light" id="prevBtn"> 
                        <i class="fa-solid fa-circle-left"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn"> 
                        <i class="fa-solid fa-circle-right"></i> <span id="nextBtnText">Selanjutnya</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/form-wizard/screening/form-submit.js') }}"></script>
    <script src="{{ asset('js/form-wizard/screening/form-wizard.js') }}"></script>
    <script src="{{ asset('js/screening/calculator.js') }}"></script>
    <script src="{{ asset('js/screening/evaluasi.js') }}"></script>
    <script src="{{ asset('js/evaluator-doctor.js') }}?v={{ time() }}"></script>
    <script>
        const metaUserRole = document.createElement('meta');
        metaUserRole.name = 'user-can-save';
        metaUserRole.content = '{{ auth()->user()->hasAnyRole(["Doktor", "Doktor Gigi"]) ? "true" : "false" }}';
        document.head.appendChild(metaUserRole);

        const metaIsPerawatUks = document.createElement('meta');
        metaIsPerawatUks.name = 'is-perawat-uks';
        metaIsPerawatUks.content = '{{ auth()->user()->hasRole("Perawat UKS") ? "true" : "false" }}';
        document.head.appendChild(metaIsPerawatUks);
        
        const metaLevel = document.createElement('meta');
        metaLevel.name = 'student-level';
        metaLevel.content = '{{ $level }}';
        document.head.appendChild(metaLevel);

        document.addEventListener('DOMContentLoaded', function() {
            const isPerawatUks = document.querySelector('meta[name="is-perawat-uks"]')?.content === 'true';
            const studentLevel = document.querySelector('meta[name="student-level"]')?.content;
            
            if (isPerawatUks) {
                let statusGiziStep = 0;
                if (studentLevel === 'smp' || studentLevel === 'sma') {
                    statusGiziStep = 1; 
                } else {
                    statusGiziStep = 0; 
                }

                if (typeof currentTab !== 'undefined') {
                    currentTab = statusGiziStep;
                }

                setTimeout(() => {
                    if (typeof showTab === 'function') {
                        showTab(statusGiziStep);
                    }
                }, 100);
            }
            
            setTimeout(() => {
                const steppers = document.querySelectorAll('#screening-stepper .step[data-step]');
                
                steppers.forEach(function(stepper) {
                    stepper.addEventListener('click', function() {
                        if (isPerawatUks) {
                            const clickedStep = parseInt(this.getAttribute('data-step'));
                            const allowedStep = (studentLevel === 'smp' || studentLevel === 'sma') ? 1 : 0;
                            
                            if (clickedStep !== allowedStep) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Akses Ditolak',
                                    text: 'Anda tidak memiliki akses ke step ini.',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                        }

                        const stepNumber = parseInt(this.getAttribute('data-step'));
                        if (!isNaN(stepNumber) && typeof currentTab !== 'undefined' && typeof showTab === 'function') {
                            currentTab = stepNumber;
                            showTab(stepNumber);
                        }
                    });
                });
            }, 500);
        });
    </script>
@endsection