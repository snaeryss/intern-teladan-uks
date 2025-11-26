<?php

namespace App\Repositories\MCU;

use App\Models\McuNutritionalStatus\DctkMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SdMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SmpMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SmaMcuNutritionalStatus;
use App\Models\Mcu;
use App\Services\NutritionalDataTransformer;
use Illuminate\Support\Facades\Log;

class NutritionalStatusRepository
{
    public function __construct(
        private NutritionalDataTransformer $transformer
    ) {}

    public function saveNutritionalStatus(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw nutritional data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            $nutritionalData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed nutritional data to save', ['level' => $groupLevel, 'data' => $nutritionalData]);

            if (empty($nutritionalData['weight']) && empty($nutritionalData['height'])) {
                Log::warning('Skipping nutritional status save - no weight/height data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $nutritionalData);

            Log::info('Nutritional status saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving nutritional status', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data status gizi: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save nutritional status', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data status gizi: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'dctk':
                DctkMcuNutritionalStatus::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sd':
                SdMcuNutritionalStatus::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'smp':
                SmpMcuNutritionalStatus::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuNutritionalStatus::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            default:
                throw new \Exception("Unknown group level: {$groupLevel}");
        }
    }
}