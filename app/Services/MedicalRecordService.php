<?php

namespace App\Services;

use App\Contracts\MedicalRecordServiceInterface;
use App\Repositories\MCU\ConclusionRepository;
use App\Repositories\DCU\DcuRepository;
use App\Repositories\DCU\DiagnosisRepository;
use App\Repositories\DCU\ExaminationRepository;
use App\Repositories\MCU\EyeEarRepository;
use App\Repositories\MCU\GeneralRepository;
use App\Repositories\MCU\HygieneRepository;
use App\Repositories\MCU\McuRepository;
use App\Repositories\MCU\MouthRepository;
use App\Repositories\MCU\NutritionalStatusRepository;
use App\Repositories\DCU\OhisRepository;
use App\Repositories\MCU\VitalsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicalRecordService implements MedicalRecordServiceInterface
{
    public function __construct(
        private ConclusionRepository $conclusionRepository,
        private DcuRepository $dcuRepository,
        private DiagnosisRepository $diagnosisRepository,
        private ExaminationRepository $examinationRepository,
        private EyeEarRepository $eyeEarRepository,
        private GeneralRepository $generalRepository,
        private HygieneRepository $hygieneRepository,
        private McuRepository $mcuRepository,
        private MouthRepository $mouthRepository,
        private NutritionalStatusRepository $nutritionalStatusRepository,
        private OhisRepository $ohisRepository,
        private VitalsRepository $vitalsRepository
    ) {}

    /**
     * Create atau update medical record
     */
    public function create(string $type, array $data)
    {
        $this->validateData($type, $data);

        Log::info("MedicalRecordService: Creating {$type}", [
            'student_id' => $data['student_id'] ?? null,
            'period_id' => $data['period_id'] ?? null,
        ]);

        return match ($type) {
            'dcu' => $this->createDentalCheckUp($data),
            'mcu', 'scr' => $this->createMedicalCheckUp($data),
            default => throw new \InvalidArgumentException("Invalid type: {$type}")
        };
    }

    /**
     * Mark medical record as finished
     */
    public function finish(string $type, int|string $id): bool
    {
        Log::info("MedicalRecordService: Finishing {$type} with ID {$id}");

        $result = match ($type) {
            'dcu' => $this->dcuRepository->finish((int) $id),
            'mcu', 'scr' => $this->mcuRepository->finish((int) $id),
            default => throw new \InvalidArgumentException("Invalid type: {$type}")
        };

        Log::info("{$type} marked as finished", ['id' => $id]);
        return $result;
    }

    /**
     * Find by ID 
     */
    public function findById(string $type, int|string $id)
    {
        return match ($type) {
            'dcu' => $this->dcuRepository->findById((int) $id),
            'mcu', 'scr' => $this->mcuRepository->findById((int) $id),
            default => throw new \InvalidArgumentException("Invalid type: {$type}")
        };
    }

    /**
     * Find by student and period
     */
    public function findByStudentAndPeriod(string $type, string $studentId, int $periodId)
    {
        return match ($type) {
            'dcu' => $this->dcuRepository->findByStudentAndPeriod($studentId, $periodId),
            'mcu', 'scr' => $this->mcuRepository->findByStudentAndPeriod($studentId, $periodId),
            default => throw new \InvalidArgumentException("Invalid type: {$type}")
        };
    }

    /**
     * Check if finished
     */
    public function isFinished(string $type, int|string $id): bool
    {
        $record = $this->findById($type, $id);
        return $record?->is_finish ?? false;
    }

    /**
     * Validate data 
     */
    public function validateData(string $type, array $data): bool
    {
        $requiredFields = ['student_id', 'period_id', 'doctor_id'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        if ($type === 'dcu') {
            if (!isset($data['dcu_date'])) {
                throw new \InvalidArgumentException("Field dcu_date is required for DCU");
            }
        } elseif (in_array($type, ['mcu', 'scr'])) {
            if (!isset($data['mcu_date'])) {
                throw new \InvalidArgumentException("Field mcu_date is required for MCU");
            }
        }

        return true;
    }

    /**
     * Create DCU 
     */
    private function createDentalCheckUp(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $dcu = $this->dcuRepository->createOrUpdate($data);

                if (!empty($data['diagnoses'])) {
                    $this->diagnosisRepository->save($dcu->id, $data['diagnoses']);
                }

                if (isset($data['oklusi'])) {
                    $this->examinationRepository->save($dcu->id, $data);
                }

                if (isset($data['di_matrix'])) {
                    $this->ohisRepository->save($dcu->id, $data);
                }

                if ($data['is_finish'] ?? false) {
                    $this->dcuRepository->finish($dcu->id);
                }

                $result = $this->dcuRepository->findById($dcu->id);

                Log::info('DCU created successfully', [
                    'dcu_id' => $result->id,
                    'code' => $result->code,
                ]);

                return $result;
            } catch (\Exception $e) {
                Log::error('Failed to create DCU', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Create MCU
     */
    private function createMedicalCheckUp(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $mcu = $this->mcuRepository->createOrUpdate($data);

                if (!empty($data['nutritional_data'])) {
                    $this->nutritionalStatusRepository->saveNutritionalStatus(
                        $mcu->mcu_id,
                        $data['nutritional_data']
                    );
                }

                if (!empty($data['vitals_data'])) {
                    $this->vitalsRepository->saveVitals(
                        $mcu->mcu_id,
                        $data['vitals_data']
                    );
                }

                if (!empty($data['eye_ear_data'])) {
                    $this->eyeEarRepository->saveEyeEar(
                        $mcu->mcu_id,
                        $data['eye_ear_data']
                    );
                }

                if (!empty($data['general_data'])) {
                    $this->generalRepository->saveGeneral(
                        $mcu->mcu_id,
                        $data['general_data']
                    );
                }

                if (!empty($data['mouth_data'])) {
                    $this->mouthRepository->saveMouth(
                        $mcu->mcu_id,
                        $data['mouth_data']
                    );
                }

                if (!empty($data['hygiene_data'])) {
                    $this->hygieneRepository->saveHygiene(
                        $mcu->mcu_id,
                        $data['hygiene_data']
                    );
                }

                if (!empty($data['conclusion_data'])) {
                    $this->conclusionRepository->saveConclusion(
                        $mcu->mcu_id,
                        $data['conclusion_data']
                    );
                }

                if ($data['is_finish'] ?? false) {
                    $this->mcuRepository->finish($mcu->mcu_id);
                }

                $result = $this->mcuRepository->findById($mcu->mcu_id);

                Log::info('MCU created successfully', [
                    'mcu_id' => $result->mcu_id,
                    'code' => $result->code,
                ]);

                return $result;
            } catch (\Exception $e) {
                Log::error('Failed to create MCU', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }
        });
    }


    public function getEvaluatorDoctorName(string $type, string $studentId, int $periodId): string
    {
        $user = auth()->user();

        $isAuthorizedDoctor = match ($type) {
            'dcu' => $user->hasRole('Doktor Gigi'),
            'mcu', 'scr' => $user->hasRole('Doktor'),
            default => false
        };

        if ($isAuthorizedDoctor) {
            return $user->name;
        }

        $record = $this->findByStudentAndPeriod($type, $studentId, $periodId);

        if (!$record || !$record->doctor) {
            return '-';
        }

        return $record->doctor->name;
    }
}
