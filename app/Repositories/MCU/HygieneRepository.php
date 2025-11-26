<?php

namespace App\Repositories\MCU;

use App\Models\McuHygiene\SmpMcuHygiene;
use App\Models\McuHygiene\SmaMcuHygiene;
use App\Models\Mcu;
use App\Services\HygieneDataTransformer;
use Illuminate\Support\Facades\Log;

class HygieneRepository
{
    public function __construct(
        private HygieneDataTransformer $transformer
    ) {}

    public function saveHygiene(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw hygiene data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for hygiene', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            if (!in_array($groupLevel, ['smp', 'sma'])) {
                Log::info('Skipping hygiene save - not SMP/SMA level', ['mcu_id' => $mcuId, 'level' => $groupLevel]);
                return;
            }

            $hygieneData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed hygiene data to save', ['level' => $groupLevel, 'data' => $hygieneData]);

            if ($this->isEmptyData($hygieneData)) {
                Log::warning('Skipping hygiene save - no meaningful data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $hygieneData);

            Log::info('Hygiene data saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving hygiene', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data kebersihan diri: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save hygiene', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data kebersihan diri: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'smp':
                SmpMcuHygiene::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuHygiene::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            default:
                throw new \Exception("Unknown group level: {$groupLevel}");
        }
    }

    private function isEmptyData(array $data): bool
    {
        unset($data['mcu_id']);

        $hasData = false;
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '' && 
                $value !== 'healthy' && $value !== 'no' && $value !== '-') {
                $hasData = true;
                break;
            }
        }

        return !$hasData;
    }
}