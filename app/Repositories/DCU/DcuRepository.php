<?php

namespace App\Repositories\DCU;

use App\Models\Dcu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class DcuRepository
{
    /**
     * Create atau update DCU record
     */
    public function createOrUpdate(array $data): Dcu
    {
        try {
            $existingDcu = $this->findByStudentAndPeriod(
                $data['student_id'],
                $data['period_id']
            );

            $dcuData = [
                'date' => $data['dcu_date'],
                'doctor_id' => $data['doctor_id'],
                'location_id' => $data['location_id'] ?? 1,
                'is_finish' => $data['is_finish'] ?? false,
            ];

            if (!$existingDcu) {
                $dcuData['code'] = $this->generateCode();
            }

            $dcu = Dcu::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'period_id' => $data['period_id'],
                ],
                $dcuData
            );

            Log::info('DCU record saved', [
                'id' => $dcu->id,
                'action' => $existingDcu ? 'updated' : 'created'
            ]);

            return $dcu;

        } catch (\Exception $e) {
            Log::error('Failed to create/update DCU', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Mark DCU as finished
     */
    public function finish(int $id): bool
    {
        try {
            $dcu = Dcu::findOrFail($id);
            $dcu->update(['is_finish' => true]);
            
            Log::info('DCU marked as finished', ['id' => $id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to finish DCU', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Find by ID
     */
    public function findById(int $id): ?Dcu
    {
        return Dcu::with([
            'student',
            'period',
            'doctor',
            'examinedByDoctor',
            'location',
            'diagnoses.dentalDiagnosis',
            'examination',
            'ohis'
        ])->find($id);
    }

    /**
     * Find by student dan period
     */
    public function findByStudentAndPeriod(string $studentId, int $periodId): ?Dcu
    {
        return Dcu::where('student_id', $studentId)
            ->where('period_id', $periodId)
            ->first();
    }

    /**
     * Get all unfinished DCU for active periods
     */
    public function getUnfinishedForActivePeriods(): \Illuminate\Database\Eloquent\Collection
    {
        return Dcu::with(['student', 'period'])
            ->whereHas('period.academicYear', function ($query) {
                $query->where('is_active', true);
            })
            ->where('is_finish', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all finished DCU for specific period
     */
    public function getFinishedByPeriod(int $periodId): \Illuminate\Database\Eloquent\Collection
    {
        return Dcu::with(['student', 'period', 'doctor'])
            ->where('period_id', $periodId)
            ->where('is_finish', true)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Delete DCU record
     */
    public function delete(int $id): bool
    {
        try {
            $dcu = Dcu::findOrFail($id);
            $dcu->delete();
            
            Log::info('DCU deleted', ['id' => $id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to delete DCU', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique DCU code
     */
    private function generateCode(): string
    {
        return 'DCU-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}