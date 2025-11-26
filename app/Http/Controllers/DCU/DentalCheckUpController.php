<?php

namespace App\Http\Controllers\DCU;

use App\Http\Controllers\Controller;
use App\Models\DentalDiagnosis;
use App\Models\Location;
use App\Models\Period;
use App\Models\Student;
use App\Repositories\StudentRepository;
use App\Services\MedicalRecordService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class DentalCheckUpController extends Controller
{
    public function __construct(
        private MedicalRecordService $medicalRecordService,
        private StudentRepository $studentRepository
    ) {}

    /**
     * Show DCU form
     */
    public function showForm(Student $student, Period $period): View
    {
        if (auth()->user()->hasRole('Perawat UKS')) {
            abort(403, 'Anda tidak memiliki akses ke form DCU.');
        }

        $dcu = $this->medicalRecordService->findByStudentAndPeriod(
            'dcu',
            $student->id,
            $period->id
        );

        $viewData = $this->prepareViewData($student, $period, $dcu);

        return view('dcu.form', $viewData);
    }

    /**
     * Save DCU step (partial save)
     */
    public function saveStep(Request $request, Student $student, Period $period): JsonResponse
    {
        Log::info('DCU saveStep', [
            'student_id' => $student->id,
            'period_id' => $period->id,
            'user_id' => auth()->id(),
        ]);

        try {
            if (auth()->user()->hasRole('Perawat UKS')) {
                return $this->errorResponse('Anda tidak memiliki akses untuk menyimpan data DCU.', 403);
            }

            $data = $this->prepareDataForSave($request, $student, $period, false);

            $dcu = $this->medicalRecordService->create('dcu', $data);

            Log::info('DCU saveStep success', ['dcu_id' => $dcu->id]);

            return response()->json([
                'success' => true,
                'message' => 'Progres berhasil disimpan',
                'dcu_id' => $dcu->id,
                'user_name' => auth()->user()->name,
            ]);

        } catch (\Exception $e) {
            Log::error('DCU saveStep failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Gagal menyimpan progres', 500, $e->getMessage());
        }
    }

    /**
     * Store DCU (final save)
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('DCU store', [
            'student_id' => $request->student_id,
            'period_id' => $request->period_id,
            'user_id' => auth()->id(),
        ]);

        try {
            if (auth()->user()->hasRole('Perawat UKS')) {
                return $this->errorResponse('Anda tidak memiliki akses untuk menyelesaikan pemeriksaan DCU.', 403);
            }

            $validated = $this->validateStoreRequest($request);

            $data = $this->prepareDataForSave($request, null, null, true);

            $dcu = $this->medicalRecordService->create('dcu', $data);

            Log::info('DCU store success', [
                'dcu_id' => $dcu->id,
                'code' => $dcu->code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pemeriksaan gigi berhasil disimpan',
                'data' => [
                    'dcu_id' => $dcu->id,
                    'code' => $dcu->code,
                    'is_finish' => $dcu->is_finish,
                ],
                'redirect_url' => route('waiting-list.index')
            ]);

        } catch (ValidationException $e) {
            Log::error('DCU validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('DCU store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Terjadi kesalahan saat menyimpan data', 500, $e->getMessage());
        }
    }

    /**
     * Get diagnoses for existing DCU
     */
    public function getDiagnoses(Student $student, Period $period): JsonResponse
    {
        try {
            $dcu = $this->medicalRecordService->findByStudentAndPeriod(
                'dcu',
                $student->id,
                $period->id
            );

            if (!$dcu) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'dcu_date' => null,
                    'message' => 'Belum ada data DCU'
                ]);
            }

            $diagnoses = $dcu->diagnoses->map(function ($diagnosis) {
                return [
                    'id' => $diagnosis->id,
                    'toothNumber' => $diagnosis->tooth_number,
                    'diagnosisId' => $diagnosis->dental_diagnosis_id,
                    'diagnosisText' => $diagnosis->dentalDiagnosis->name ?? '-',
                    'notes' => $diagnosis->notes ?? '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $diagnoses,
                'dcu_id' => $dcu->id,
                'dcu_date' => $dcu->date ? $dcu->date->format('Y-m-d') : null
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get diagnoses', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'period_id' => $period->id
            ]);

            return $this->errorResponse('Gagal mengambil data diagnosis', 500, $e->getMessage());
        }
    }

    /**
     * Prepare view data
     */
    private function prepareViewData(Student $student, Period $period, $dcu): array
    {
        $currentClass = $this->studentRepository->getCurrentClass($student);
        $age = Carbon::parse($student->date_birth)
            ->diff(Carbon::now())
            ->format('%y tahun %m bulan');

        $location = session('selected_location') ?? Location::where('is_active', 1)->first();
        $dentalDiagnoses = DentalDiagnosis::all();

        return [
            'student' => $student,
            'period' => $period,
            'dcu' => $dcu,
            'current_class' => $currentClass,
            'age' => $age,
            'title' => 'Formulir Dental Check Up',
            'levelName' => $this->studentRepository->getLevelName($student),
            'level' => $this->studentRepository->getLevelCode($student),
            'location' => $location,
            'dentalDiagnoses' => $dentalDiagnoses,
        ];
    }

    /**
     * Prepare data for save
     */
    private function prepareDataForSave(Request $request, ?Student $student = null, ?Period $period = null, bool $isFinish = false): array
    {
        $data = $request->all();

        if ($student && $period) {
            $data['student_id'] = $student->id;
            $data['period_id'] = $period->id;
        }

        $data['doctor_id'] = auth()->id();

        $data['location_id'] = $request->input('location_id', 1);
        $data['dcu_date'] = $request->input('dcu_date') ?: now()->format('Y-m-d');
        $data['is_finish'] = $isFinish;

        return $data;
    }

    /**
     * Validate store request
     */
    private function validateStoreRequest(Request $request): array
    {
        return $request->validate([
            'student_id' => 'required|uuid|exists:students,id',
            'period_id' => 'required|integer|exists:periods,id',
            'doctor_id' => 'required|exists:users,id',
            'location_id' => 'required|integer|exists:locations,id',
            'dcu_date' => 'required|date',
            'is_finish' => 'nullable|boolean',
            'diagnoses' => 'nullable|array',
            'diagnoses.*.tooth_number' => 'required_with:diagnoses|string',
            'diagnoses.*.diagnosis_id' => 'required_with:diagnoses|exists:dental_diagnoses,id',
            'diagnoses.*.notes' => 'nullable|string',
            'oklusi' => 'nullable|string',
            'mukosa' => 'nullable|string',
            'dmf_d' => 'nullable|numeric|min:0',
            'dmf_m' => 'nullable|numeric|min:0',
            'dmf_f' => 'nullable|numeric|min:0',
            'frekuensi_sikat' => 'nullable|string',
            'waktu_sikat' => 'nullable|string',
            'pasta_gigi' => 'nullable|in:Ya,Tidak',
            'makanan_manis' => 'nullable|in:Ya,Tidak',
            'di_matrix' => 'nullable|array',
            'di_matrix.*.*' => 'nullable|numeric|min:0|max:3',
            'ci_matrix' => 'nullable|array',
            'ci_matrix.*.*' => 'nullable|numeric|min:0|max:3',
            'ohis_keterangan' => 'nullable|string',
        ]);
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
     */
    public function getEvaluatorDoctorName(Student $student, Period $period): JsonResponse
    {
        try {
            $doctorName = $this->medicalRecordService->getEvaluatorDoctorName(
                'dcu',
                $student->id,
                $period->id
            );

            return response()->json([
                'success' => true,
                'doctor_name' => $doctorName,
                'is_current_user' => auth()->user()->hasRole('Doktor Gigi')
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