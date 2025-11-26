<?php

namespace App\Repositories\MCU;

use App\Models\Mcu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class McuRepository
{
    /**
     * Create atau update MCU record
     */
    public function createOrUpdate(array $data): Mcu
    {
        try {
            $existingMcu = $this->findByStudentAndPeriod(
                $data['student_id'],
                $data['period_id']
            );

            $mcuData = [
                'date' => $data['mcu_date'],
                'doctor_id' => $data['doctor_id'],
                'location_id' => $data['location_id'] ?? 1,
                'is_finish' => $data['is_finish'] ?? false,
            ];

            if (!$existingMcu) {
                $mcuData['code'] = $this->generateCode();
                Log::info('Creating new MCU', [
                    'student_id' => $data['student_id'],
                    'period_id' => $data['period_id'],
                    'code' => $mcuData['code']
                ]);
            } else {
                Log::info('Updating existing MCU', [
                    'mcu_id' => $existingMcu->mcu_id,
                    'code' => $existingMcu->code
                ]);
            }

            $mcu = Mcu::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'period_id' => $data['period_id'],
                ],
                $mcuData
            );

            Log::info('MCU record saved', [
                'mcu_id' => $mcu->mcu_id,
                'action' => $existingMcu ? 'updated' : 'created'
            ]);

            return $mcu;

        } catch (\Exception $e) {
            Log::error('Failed to create/update MCU', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Mark MCU as finished
     */
    public function finish(int $id): bool
    {
        try {
            $mcu = Mcu::findOrFail($id);
            $mcu->update(['is_finish' => true]);
            
            Log::info('MCU marked as finished', ['mcu_id' => $id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to finish MCU', [
                'error' => $e->getMessage(),
                'mcu_id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Find by ID
     */
    public function findById(int $id): ?Mcu
    {
        $mcu = Mcu::with([
            'student',
            'period',
            'doctor',
            'location'
        ])->find($id);

        if ($mcu) {
            $mcu->loadNutritionalStatus();
        }

        return $mcu;
    }

    /**
     * Find by student
     */
    public function findByStudentAndPeriod(string $studentId, int $periodId): ?Mcu
    {
        return Mcu::where('student_id', $studentId)
            ->where('period_id', $periodId)
            ->first();
    }

    /**
     * Get all unfinished MCU for active periods
     */
    public function getUnfinishedForActivePeriods(): \Illuminate\Database\Eloquent\Collection
    {
        return Mcu::with(['student', 'period'])
            ->whereHas('period.academicYear', function ($query) {
                $query->where('is_active', true);
            })
            ->where('is_finish', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unfinished by type (MCU or SCR)
     */
    public function getUnfinishedByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return Mcu::with(['student', 'period'])
            ->whereHas('period.academicYear', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('period', function ($query) use ($type) {
                $query->whereRaw('UPPER(name) = ?', [strtoupper($type)]);
            })
            ->where('is_finish', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all finished MCU
     */
    public function getFinishedByPeriod(int $periodId): \Illuminate\Database\Eloquent\Collection
    {
        return Mcu::with(['student', 'period', 'doctor'])
            ->where('period_id', $periodId)
            ->where('is_finish', true)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Delete MCU record
     */
    public function delete(int $id): bool
    {
        try {
            $mcu = Mcu::findOrFail($id);
            $mcu->delete();
            
            Log::info('MCU deleted', ['mcu_id' => $id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to delete MCU', [
                'error' => $e->getMessage(),
                'mcu_id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique MCU code
     */
    private function generateCode(): string
    {
        return 'MCU-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}