<?php

namespace App\Repositories\MCU;

use App\Models\McuVitals\SmpMcuVitals;
use App\Models\McuVitals\SmaMcuVitals;
use App\Models\Mcu;
use App\Services\VitalsDataTransformer;
use Illuminate\Support\Facades\Log;

class VitalsRepository
{
    public function __construct(
        private VitalsDataTransformer $transformer
    ) {}

    public function saveVitals(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw vitals data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for vitals', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            if (!in_array($groupLevel, ['smp', 'sma'])) {
                Log::info('Skipping vitals save - not SMP/SMA level', ['mcu_id' => $mcuId, 'level' => $groupLevel]);
                return;
            }

            $vitalsData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed vitals data to save', ['level' => $groupLevel, 'data' => $vitalsData]);

            if (empty($vitalsData['systolic_blood_pressure']) && empty($vitalsData['heart_rate'])) {
                Log::warning('Skipping vitals save - no vital signs data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $vitalsData);

            Log::info('Vitals saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving vitals', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data tanda vital: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save vitals', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data tanda vital: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'smp':
                SmpMcuVitals::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuVitals::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            default:
                throw new \Exception("Unknown group level: {$groupLevel}");
        }
    }
}