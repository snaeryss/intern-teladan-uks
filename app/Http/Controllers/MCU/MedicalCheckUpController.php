<?php

namespace App\Http\Controllers\MCU;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Student;
use App\Repositories\StudentRepository;
use App\Services\BmiEvaluationService;
use App\Services\MedicalRecordService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class MedicalCheckUpController extends Controller
{
    public function __construct(
        private StudentRepository $studentRepository,
        private BmiEvaluationService $bmiEvaluationService,
        private MedicalRecordService $medicalRecordService
    ) {}

    /**
     * Show MCU/SCR form
     */
    public function show(Student $student, Period $period): View
    {
        $viewData = $this->prepareViewData($student, $period);
        return view('screening.form', $viewData);
    }

    /**
     * Evaluate BMI
     */
    public function evaluateBmi(Request $request): JsonResponse
    {
        Log::info('BMI Evaluation Request', $request->all());

        try {
            $validated = $request->validate([
                'student_id' => 'required|string',
                'weight' => 'required|numeric|min:1|max:500',
                'height' => 'required|numeric|min:30|max:250',
            ]);

            $result = $this->bmiEvaluationService->evaluate(
                $validated['student_id'],
                $validated['weight'],
                $validated['height']
            );

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save MCU step (partial save)
     */
    public function saveStep(Request $request, Student $student, Period $period): JsonResponse
    {
        Log::info('MCU saveStep', [
            'student_id' => $student->id,
            'period_id' => $period->id,
            'user_id' => auth()->id(),
        ]);

        try {
            $this->checkPerawatAccess($request, $student);

            $data = $this->prepareDataForSave($request, $student, $period, false);

            $mcu = $this->medicalRecordService->create('mcu', $data);

            Log::info('MCU saveStep success', ['mcu_id' => $mcu->mcu_id]);

            return response()->json([
                'success' => true,
                'message' => 'Progres berhasil disimpan',
                'mcu_id' => $mcu->mcu_id,
                'data' => [
                    'code' => $mcu->code,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('MCU saveStep failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Gagal menyimpan progres', 500, $e->getMessage());
        }
    }

    /**
     * Store MCU (final save)
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('MCU store', [
            'student_id' => $request->student_id,
            'period_id' => $request->period_id,
            'user_id' => auth()->id(),
        ]);

        try {
            if (auth()->user()->hasRole('Perawat UKS')) {
                return $this->errorResponse('Anda tidak memiliki akses untuk menyelesaikan pemeriksaan lengkap.', 403);
            }

            // Step 1: Flexible validation (all nullable strings)
            $validated = $this->validateStoreRequest($request);

            // Step 2: Sanitize enum values (optional but recommended)
            $this->sanitizeEnumValues($validated);

            // Step 3: Prepare data for saving
            $data = $this->prepareDataForSave($request, null, null, true);

            // Step 4: Save to database
            $mcu = $this->medicalRecordService->create('mcu', $data);

            Log::info('MCU store success', [
                'mcu_id' => $mcu->mcu_id,
                'code' => $mcu->code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pemeriksaan screening berhasil disimpan',
                'data' => [
                    'mcu_id' => $mcu->mcu_id,
                    'code' => $mcu->code,
                    'is_finish' => $mcu->is_finish,
                ],
                'redirect_url' => route('waiting-list.index')
            ]);
        } catch (ValidationException $e) {
            Log::error('MCU validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('MCU store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Terjadi kesalahan saat menyimpan data', 500, $e->getMessage());
        }
    }
    /**
     * Get existing MCU data
     */
    public function getExistingData(Student $student, Period $period): JsonResponse
    {
        try {
            $mcu = $this->medicalRecordService->findByStudentAndPeriod(
                'mcu',
                $student->id,
                $period->id
            );

            if (!$mcu) {
                return response()->json([
                    'success' => true,
                    'has_data' => false,
                    'message' => 'Belum ada data tersimpan di database'
                ]);
            }

            $formData = $this->transformMcuToFormData($mcu, $student);

            Log::info('MCU data retrieved', [
                'mcu_id' => $mcu->mcu_id,
                'student_id' => $student->id,
                'has_nutritional_data' => !is_null($mcu->nutritionalStatus),
            ]);

            return response()->json([
                'success' => true,
                'has_data' => true,
                'data' => $formData,
                'message' => 'Data berhasil dimuat dari database'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get MCU data', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'period_id' => $period->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Gagal mengambil data', 500, $e->getMessage());
        }
    }

    /**
     * Prepare view data
     */
    private function prepareViewData(Student $student, Period $period): array
    {
        $mcu = $this->medicalRecordService->findByStudentAndPeriod(
            'mcu',
            $student->id,
            $period->id
        );

        $currentClass = $this->studentRepository->getCurrentClass($student);
        $age = Carbon::parse($student->date_birth)
            ->diff(Carbon::now())
            ->format('%y tahun %m bulan');

        return [
            'student' => $student,
            'period' => $period,
            'current_class' => $currentClass,
            'mcu' => $mcu,
            'age' => $age,
            'title' => 'Formulir Screening',
            'levelName' => $this->studentRepository->getLevelName($student),
            'level' => $this->studentRepository->getLevelCode($student),
        ];
    }

    /**
     * Check Perawat UKS access untuk step tertentu
     */
    private function checkPerawatAccess(Request $request, Student $student): void
    {
        if (!auth()->user()->hasRole('Perawat UKS')) {
            return;
        }

        $groupLevel = $student->school_level->getGroupLevel();
        $allowedStep = in_array($groupLevel, ['smp', 'sma']) ? 1 : 0;
        $stepNumber = $request->input('step_number');

        Log::info('Perawat UKS Access Check', [
            'group_level' => $groupLevel,
            'allowed_step' => $allowedStep,
            'requested_step' => $stepNumber
        ]);

        if ($stepNumber !== null && $stepNumber != $allowedStep) {
            Log::warning('Perawat UKS Access Denied', [
                'user_id' => auth()->id(),
                'requested_step' => $stepNumber,
                'allowed_step' => $allowedStep
            ]);

            throw new \Exception('Anda tidak memiliki akses ke step ini. Hanya dapat mengakses step Status Gizi.');
        }
    }

    /**
     * Prepare data for save
     */
    private function prepareDataForSave(Request $request, ?Student $student = null, ?Period $period = null, bool $isFinish = false): array
    {
        $data = [
            'doctor_id' => auth()->id(),
            'location_id' => $request->input('location_id', 1),
            'mcu_date' => $request->input('tanggal_periksa') ?: now()->format('Y-m-d'),
            'is_finish' => $isFinish,
        ];

        if ($student && $period) {
            $data['student_id'] = $student->id;
            $data['period_id'] = $period->id;
        } else {
            $data['student_id'] = $request->input('student_id');
            $data['period_id'] = $request->input('period_id');
        }

        if ($request->filled(['berat_badan', 'tinggi_badan'])) {
            $data['nutritional_data'] = $this->prepareNutritionalData($request, $student);
        }

        if ($request->hasAny(['tekanan_darah_sistolik', 'denyut_nadi', 'suhu'])) {
            $data['vitals_data'] = $this->prepareVitalsData($request);
        }

        if ($request->hasAny(['mata_luar', 'tajam_penglihatan', 'telinga_luar', 'serumen'])) {
            $data['eye_ear_data'] = $this->prepareEyeEarData($request);
        }

        if ($this->hasGeneralData($request)) {
            $data['general_data'] = $this->prepareGeneralData($request);
        }

        if ($request->hasAny(['celah_bibir', 'luka_sudut_mulut', 'sariawan', 'lidah_kotor', 'luka_lainnya', 'caries', 'gigi_depan'])) {
            $data['mouth_data'] = $this->prepareMouthData($request);
        }

        if ($request->hasAny(['rambut', 'kulit_bercak', 'kulit_bersisik', 'kuku'])) {
            $data['hygiene_data'] = $this->prepareHygieneData($request);
        }

        if ($request->hasAny(['kesimpulan', 'saran', 'follow_up', 'diagnosis', 'treatment', 'notes'])) {
            $data['conclusion_data'] = $this->prepareConclusionData($request);
        }

        return $data;
    }

    /**
     * Prepare nutritional data
     */
    private function prepareNutritionalData(Request $request, ?Student $student): array
    {
        $nutritionalData = [
            'weight' => $request->input('berat_badan'),
            'height' => $request->input('tinggi_badan'),
            'bmi' => $request->input('imt'),
            'nutritional_status' => $request->input('status_gizi'),
            'anemia' => $request->input('anemia') ?? $request->input('anemi'),
        ];

        if ($student) {
            $groupLevel = $student->school_level->getGroupLevel();
        } else {
            $studentId = $request->input('student_id');
            $student = Student::find($studentId);
            $groupLevel = $student->school_level->getGroupLevel();
        }

        if (in_array($groupLevel, ['dctk', 'sd'])) {
            $nutritionalData['head_circumference'] = $request->input('lingkar_kepala');
            $nutritionalData['arm_circumference'] = $request->input('lingkar_lengan_atas');
            $nutritionalData['abdominal_circumference'] = $request->input('lingkar_perut');
            $nutritionalData['weight_for_age'] = $request->input('bb_u');
        } elseif (in_array($groupLevel, ['smp', 'sma'])) {
            $nutritionalData['height_for_age'] = $request->input('tb_u');
        }

        return $nutritionalData;
    }

    private function prepareVitalsData(Request $request): array
    {
        return [
            'tekanan_darah_sistolik' => $request->input('tekanan_darah_sistolik'),
            'tekanan_darah_diastolik' => $request->input('tekanan_darah_diastolik'),
            'denyut_nadi' => $request->input('denyut_nadi'),
            'frekuensi_nafas' => $request->input('frekuensi_nafas'),
            'suhu' => $request->input('suhu'),
            'bising_jantung' => $request->input('bising_jantung', 'tidak'),
            'bising_paru' => $request->input('bising_paru', 'tidak'),
        ];
    }

    private function prepareEyeEarData(Request $request): array
    {
        return [
            // Eye fields
            'mata_luar' => $request->input('mata_luar'),
            'mata_luar_ket' => $request->input('mata_luar_ket'),
            'tajam_penglihatan' => $request->input('tajam_penglihatan'),
            'tajam_penglihatan_ket' => $request->input('tajam_penglihatan_ket'),
            'kacamata' => $request->input('kacamata'),
            'kacamata_ket' => $request->input('kacamata_ket'),
            'buta_warna' => $request->input('buta_warna'),
            'infeksi_mata' => $request->input('infeksi_mata'),
            'infeksi_mata_ket' => $request->input('infeksi_mata_ket'),
            'penglihatan_lainnya' => $request->input('penglihatan_lainnya'),

            'telinga_luar' => $request->input('telinga_luar'),
            'telinga_luar_ket' => $request->input('telinga_luar_ket'),
            'serumen' => $request->input('serumen'),
            'serumen_ket' => $request->input('serumen_ket'),
            'infeksi_telinga' => $request->input('infeksi_telinga'),
            'infeksi_telinga_ket' => $request->input('infeksi_telinga_ket'),
            'tajam_pendengaran' => $request->input('tajam_pendengaran'),
            'tajam_pendengaran_ket' => $request->input('tajam_pendengaran_ket'),
            'pendengaran_lainnya' => $request->input('pendengaran_lainnya'),
        ];
    }

    private function hasGeneralData(Request $request): bool
    {
        return $request->hasAny([
            'mata',
            'hidung',
            'mulut',
            'jantung',
            'paru',
            'neurologi',
            'rambut',
            'kulit',
            'kuku'
        ]);
    }

    private function prepareGeneralData(Request $request): array
    {
        return [
            'mata' => $request->input('mata'),
            'mata_ket' => $request->input('mata_ket'),
            'hidung' => $request->input('hidung'),
            'hidung_ket' => $request->input('hidung_ket'),
            'mulut' => $request->input('mulut'),
            'mulut_ket' => $request->input('mulut_ket'),
            'jantung' => $request->input('jantung'),
            'jantung_ket' => $request->input('jantung_ket'),
            'paru' => $request->input('paru'),
            'paru_ket' => $request->input('paru_ket'),
            'neurologi' => $request->input('neurologi'),
            'neurologi_ket' => $request->input('neurologi_ket'),
            'rambut' => $request->input('general_rambut'),
            'rambut_ket' => $request->input('general_rambut_ket'),
            'kulit' => $request->input('general_kulit'),
            'kulit_ket' => $request->input('general_kulit_ket'),
            'kuku' => $request->input('general_kuku'),
            'kuku_ket' => $request->input('general_kuku_ket'),
        ];
    }

    private function prepareMouthData(Request $request): array
    {
        return [

            'celah_bibir' => $request->input('celah_bibir'),
            'luka_sudut_mulut' => $request->input('luka_sudut_mulut'),
            'sariawan' => $request->input('sariawan'),
            'lidah_kotor' => $request->input('lidah_kotor'),
            'luka_lainnya' => $request->input('luka_lainnya'),
            'mulut_lainnya' => $request->input('mulut_lainnya'),

            'caries' => $request->input('caries'),
            'caries_ket' => $request->input('caries_ket'),
            'gigi_depan' => $request->input('gigi_depan'),
            'gigi_lainnya' => $request->input('gigi_lainnya'),
        ];
    }

    private function prepareHygieneData(Request $request): array
    {
        return [
            'rambut' => $request->input('hygiene_rambut'),
            'kulit_bercak' => $request->input('kulit_bercak'),
            'kulit_bercak_ket' => $request->input('kulit_bercak_ket'),
            'kulit_bersisik' => $request->input('kulit_bersisik'),
            'kulit_memar' => $request->input('kulit_memar'),
            'kulit_sayatan' => $request->input('kulit_sayatan'),
            'kulit_koreng' => $request->input('kulit_koreng'),
            'luka_koreng_sukar' => $request->input('luka_koreng_sukar'),
            'kulit_suntikan' => $request->input('kulit_suntikan'),
            'kuku' => $request->input('hygiene_kuku'),
        ];
    }

    private function prepareConclusionData(Request $request): array
    {
        return [
            'diagnosis' => $request->input('diagnosis') ?? $request->input('kesimpulan'),
            'treatment' => $request->input('treatment') ?? $request->input('saran'),
            'notes' => $request->input('notes') ?? $request->input('follow_up') ?? $request->input('catatan'),
        ];
    }


    /**
     * Transform MCU model to form data
     */
    private function transformMcuToFormData($mcu, Student $student): array
    {
        $formData = [
            'mcu_id' => $mcu->mcu_id,
            'tanggal_periksa' => $mcu->date,
            'is_finish' => $mcu->is_finish,
            'doctor_name' => $mcu->doctor ? $mcu->doctor->name : null,
        ];

        $nutritionalStatus = $mcu->nutritionalStatus;

        if ($nutritionalStatus) {
            $formData['berat_badan'] = $nutritionalStatus->weight;
            $formData['tinggi_badan'] = $nutritionalStatus->height;
            $formData['imt'] = $nutritionalStatus->bmi;
            $formData['status_gizi'] = $nutritionalStatus->nutritional_status?->label();
            $formData['anemia'] = $nutritionalStatus->anemia?->value;

            $groupLevel = $student->school_level->getGroupLevel();

            if (in_array($groupLevel, ['dctk', 'sd'])) {
                $formData['lingkar_kepala'] = $nutritionalStatus->head_circumference;
                $formData['lingkar_lengan_atas'] = $nutritionalStatus->arm_circumference;
                $formData['lingkar_perut'] = $nutritionalStatus->abdominal_circumference;
                $formData['bb_u'] = $nutritionalStatus->weight_for_age?->value;
            } elseif (in_array($groupLevel, ['smp', 'sma'])) {
                $formData['tb_u'] = $nutritionalStatus->height_for_age?->value;
            }
        }

        $vitals = $mcu->vitals;
        if ($vitals) {
            $formData['tekanan_darah_sistolik'] = $vitals->systolic_blood_pressure;
            $formData['tekanan_darah_diastolik'] = $vitals->diastolic_blood_pressure;
            $formData['denyut_nadi'] = $vitals->heart_rate;
            $formData['frekuensi_nafas'] = $vitals->respiratory_rate;
            $formData['suhu'] = $vitals->temperature;
            $formData['bising_jantung'] = $vitals->heart_murmur?->value;
            $formData['bising_paru'] = $vitals->lung_murmur?->value;
        }

        $eyeEar = $mcu->eyeEar;
        if ($eyeEar) {
            $groupLevel = $student->school_level->getGroupLevel();

            // Common fields for all levels
            $formData['mata_luar'] = $eyeEar->outer_eye?->value;
            $formData['tajam_penglihatan'] = $eyeEar->visual_acuity?->value;
            $formData['infeksi_mata'] = $eyeEar->eye_infection?->value;
            $formData['telinga_luar'] = $eyeEar->outer_ear?->value;
            $formData['serumen'] = $eyeEar->earwax?->value;
            $formData['infeksi_telinga'] = $eyeEar->ear_infection?->value;

            if (in_array($groupLevel, ['dctk', 'sd'])) {
                $formData['mata_luar_ket'] = $eyeEar->outer_eye_notes;
                $formData['tajam_penglihatan_ket'] = $eyeEar->visual_acuity_notes;
                $formData['kacamata'] = $eyeEar->glasses?->value;
                $formData['kacamata_ket'] = $eyeEar->glasses_notes;
                $formData['infeksi_mata_ket'] = $eyeEar->eye_infection_notes;
                $formData['penglihatan_lainnya'] = $eyeEar->other_eye_problems;
                $formData['telinga_luar_ket'] = $eyeEar->outer_ear_notes;
                $formData['serumen_ket'] = $eyeEar->earwax_notes;
                $formData['infeksi_telinga_ket'] = $eyeEar->ear_infection_notes;
                $formData['tajam_pendengaran'] = $eyeEar->hearing_acuity?->value;
                $formData['tajam_pendengaran_ket'] = $eyeEar->hearing_acuity_notes;
                $formData['pendengaran_lainnya'] = $eyeEar->other_ear_problems;
            } elseif (in_array($groupLevel, ['smp', 'sma'])) {
                $formData['tajam_penglihatan_ket'] = $eyeEar->visual_acuity_notes;
                $formData['buta_warna'] = $eyeEar->color_blindness?->value;
                $formData['pendengaran_lainnya'] = $eyeEar->other_ear_problems;
            }
        }

        $general = $mcu->general;
        if ($general) {
            $formData['mata'] = $general->eyes_hygiene?->value;
            $formData['mata_ket'] = $general->eyes_hygiene_notes;
            $formData['hidung'] = $general->nose_hygiene?->value;
            $formData['hidung_ket'] = $general->nose_hygiene_notes;
            $formData['mulut'] = $general->oral_cavity?->value;
            $formData['mulut_ket'] = $general->oral_cavity_notes;
            $formData['jantung'] = $general->heart?->value;
            $formData['jantung_ket'] = $general->heart_notes;
            $formData['paru'] = $general->lungs?->value;
            $formData['paru_ket'] = $general->lungs_notes;
            $formData['neurologi'] = $general->neurology?->value;
            $formData['neurologi_ket'] = $general->neurology_notes;
            $formData['general_rambut'] = $general->hair?->value;
            $formData['general_rambut_ket'] = $general->hair_notes;
            $formData['general_kulit'] = $general->skin?->value;
            $formData['general_kulit_ket'] = $general->skin_notes;
            $formData['general_kuku'] = $general->nails?->value;
            $formData['general_kuku_ket'] = $general->nails_notes;
        }

        $mouth = $mcu->mouth;
        if ($mouth) {
            $formData['celah_bibir'] = $mouth->oral_cleft?->value;
            $formData['luka_sudut_mulut'] = $mouth->angular_cheilitis?->value;
            $formData['sariawan'] = $mouth->stomatitis?->value;
            $formData['lidah_kotor'] = $mouth->coated_tongue?->value;
            $formData['luka_lainnya'] = $mouth->other_lesions?->value;
            $formData['mulut_lainnya'] = $mouth->other_mouth_problems;

            $groupLevel = $student->school_level->getGroupLevel();
            if (in_array($groupLevel, ['dctk', 'sd'])) {
                $formData['caries'] = $mouth->caries?->value;
                $formData['caries_ket'] = $mouth->caries_notes;
                $formData['gigi_depan'] = $mouth->misaligned_teeth?->value;
                $formData['gigi_lainnya'] = $mouth->other_teeth_problems;
            }
        }

        $hygiene = $mcu->hygiene;
        if ($hygiene) {
            $formData['hygiene_rambut'] = $hygiene->hair?->value;
            $formData['kulit_bercak'] = $hygiene->skin_patches?->value;
            $formData['kulit_bercak_ket'] = $hygiene->skin_patches_notes;
            $formData['kulit_bersisik'] = $hygiene->scaly_skin?->value;
            $formData['kulit_memar'] = $hygiene->bruised_skin?->value;
            $formData['kulit_sayatan'] = $hygiene->cut_skin?->value;
            $formData['kulit_koreng'] = $hygiene->sores?->value;
            $formData['luka_koreng_sukar'] = $hygiene->hard_to_heal_sores?->value;
            $formData['kulit_suntikan'] = $hygiene->injection_marks?->value;
            $formData['hygiene_kuku'] = $hygiene->nails?->value;
        }

        $conclusion = $mcu->conclusion;
        if ($conclusion) {
            $formData['kesimpulan'] = $conclusion->diagnosis;
            $formData['diagnosis'] = $conclusion->diagnosis;
            $formData['saran'] = $conclusion->treatment;
            $formData['treatment'] = $conclusion->treatment;
            $formData['follow_up'] = $conclusion->notes;
            $formData['notes'] = $conclusion->notes;
            $formData['catatan'] = $conclusion->notes;
        }

        return $formData;
    }

    /**
     * Validate store request
     */
    private function validateStoreRequest(Request $request): array
    {
        return $request->validate([
            'student_id' => 'required|uuid|exists:students,id',
            'period_id' => 'required|integer|exists:periods,id',
            'doctor_id' => 'nullable|exists:users,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'tanggal_periksa' => 'nullable|date',
            'is_finish' => 'nullable|boolean',

            // Nutritional
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'lingkar_kepala' => 'nullable|numeric|min:0',
            'lingkar_lengan_atas' => 'nullable|numeric|min:0',
            'lingkar_perut' => 'nullable|numeric|min:0',
            'imt' => 'nullable|string',
            'status_gizi' => 'nullable|string',
            'bb_u' => 'nullable|string',
            'tb_u' => 'nullable|string',
            'anemia' => 'nullable|string',
            'anemi' => 'nullable|string',

            // Vitals
            'tekanan_darah_sistolik' => 'nullable|integer|min:0|max:300',
            'tekanan_darah_diastolik' => 'nullable|integer|min:0|max:300',
            'denyut_nadi' => 'nullable|integer|min:0|max:300',
            'frekuensi_nafas' => 'nullable|integer|min:0|max:100',
            'suhu' => 'nullable|numeric|min:30|max:45',
            'bising_jantung' => 'nullable|string',
            'bising_paru' => 'nullable|string',

            // Eye ear
            'mata_luar' => 'nullable|string',
            'mata_luar_ket' => 'nullable|string',
            'tajam_penglihatan' => 'nullable|string',
            'tajam_penglihatan_ket' => 'nullable|string',
            'kacamata' => 'nullable|string',
            'kacamata_ket' => 'nullable|string',
            'buta_warna' => 'nullable|string',
            'infeksi_mata' => 'nullable|string',
            'infeksi_mata_ket' => 'nullable|string',
            'penglihatan_lainnya' => 'nullable|string',
            'telinga_luar' => 'nullable|string',
            'telinga_luar_ket' => 'nullable|string',
            'serumen' => 'nullable|string',
            'serumen_ket' => 'nullable|string',
            'infeksi_telinga' => 'nullable|string',
            'infeksi_telinga_ket' => 'nullable|string',
            'tajam_pendengaran' => 'nullable|string',
            'tajam_pendengaran_ket' => 'nullable|string',
            'pendengaran_lainnya' => 'nullable|string',

            'mata' => 'nullable|string',
            'mata_ket' => 'nullable|string',
            'hidung' => 'nullable|string',
            'hidung_ket' => 'nullable|string',
            'mulut' => 'nullable|string',
            'mulut_ket' => 'nullable|string',
            'jantung' => 'nullable|string',
            'jantung_ket' => 'nullable|string',
            'paru' => 'nullable|string',
            'paru_ket' => 'nullable|string',
            'neurologi' => 'nullable|string',
            'neurologi_ket' => 'nullable|string',
            'general_rambut' => 'nullable|string',
            'general_rambut_ket' => 'nullable|string',
            'general_kulit' => 'nullable|string',
            'general_kulit_ket' => 'nullable|string',
            'general_kuku' => 'nullable|string',
            'general_kuku_ket' => 'nullable|string',

            'celah_bibir' => 'nullable|string',
            'luka_sudut_mulut' => 'nullable|string',
            'sariawan' => 'nullable|string',
            'lidah_kotor' => 'nullable|string',
            'luka_lainnya' => 'nullable|string',
            'mulut_lainnya' => 'nullable|string',
            'caries' => 'nullable|string',
            'caries_ket' => 'nullable|string',
            'gigi_depan' => 'nullable|string',
            'gigi_lainnya' => 'nullable|string',

            'hygiene_rambut' => 'nullable|string',
            'kulit_bercak' => 'nullable|string',
            'kulit_bercak_ket' => 'nullable|string',
            'kulit_bersisik' => 'nullable|string',
            'kulit_memar' => 'nullable|string',
            'kulit_sayatan' => 'nullable|string',
            'kulit_koreng' => 'nullable|string',
            'luka_koreng_sukar' => 'nullable|string',
            'kulit_suntikan' => 'nullable|string',
            'hygiene_kuku' => 'nullable|string',

            'kesimpulan' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'saran' => 'nullable|string',
            'treatment' => 'nullable|string',
            'follow_up' => 'nullable|string',
            'notes' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
    }

    // Add custom validation
    private function sanitizeEnumValues(array &$data): void
    {
        $enumMappings = [
            // Yes/No mappings
            'ya' => 'ya',
            'yes' => 'ya',
            'tidak' => 'tidak',
            'no' => 'tidak',
            'tidak ada' => 'tidak',

            // Normal/Abnormal mappings
            'normal' => 'normal',
            'sehat' => 'normal',
            'abnormal' => 'tidak',
            'tidak sehat' => 'tidak',

            // Visual acuity specific
            'lowvision' => 'lowvision',
            'low vision' => 'lowvision',
            'kebutaan' => 'kebutaan',
            'kelainan_refraksi' => 'kelainan_refraksi',
            'kelainan refraksi' => 'kelainan_refraksi',

            // Hearing specific
            'gangguan' => 'gangguan',
            'gangguan pendengaran' => 'gangguan',

            // Hygiene specific
            'kotor' => 'kotor',
            'bersih' => 'sehat',
        ];

        $fieldsToSanitize = [
            'mata_luar',
            'tajam_penglihatan',
            'kacamata',
            'infeksi_mata',
            'telinga_luar',
            'serumen',
            'infeksi_telinga',
            'tajam_pendengaran',
            'mata',
            'hidung',
            'mulut',
            'jantung',
            'paru',
            'neurologi',
            'celah_bibir',
            'luka_sudut_mulut',
            'sariawan',
            'lidah_kotor',
            'luka_lainnya',
            'caries',
            'gigi_depan',
            'general_rambut',
            'general_kulit',
            'general_kuku',
            'hygiene_rambut',
            'kulit_bercak',
            'kulit_bersisik',
            'kulit_memar',
            'kulit_sayatan',
            'kulit_koreng',
            'luka_koreng_sukar',
            'kulit_suntikan',
            'hygiene_kuku',
            'bising_jantung',
            'bising_paru',
            'buta_warna'
        ];

        foreach ($fieldsToSanitize as $field) {
            if (isset($data[$field])) {
                $value = strtolower(trim($data[$field]));

                if (isset($enumMappings[$value])) {
                    $data[$field] = $enumMappings[$value];
                }

                $data[$field] = $value;
            }
        }
    }

    /**
     * Return error response
     */
    private function errorResponse(string $message, int $code = 500, ?string $error = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $code);
    }

    /**
     * Get evaluator doctor name
     * 
     * @param Student $student
     * @param Period $period
     * @return JsonResponse
     */
    public function getEvaluatorDoctorName(Student $student, Period $period): JsonResponse
    {
        try {
            $doctorName = $this->medicalRecordService->getEvaluatorDoctorName(
                'mcu',
                $student->id,
                $period->id
            );

            return response()->json([
                'success' => true,
                'doctor_name' => $doctorName,
                'is_current_user' => auth()->user()->hasRole('Doktor')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get evaluator doctor name', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil nama dokter'
            ], 500);
        }
    }
}
