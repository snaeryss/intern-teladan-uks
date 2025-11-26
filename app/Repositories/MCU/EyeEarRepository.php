<?php

namespace App\Repositories\MCU;

use App\Models\McuEyeEar\DctkMcuEyeEar;
use App\Models\McuEyeEar\SdMcuEyeEar;
use App\Models\McuEyeEar\SmpMcuEyeEar;
use App\Models\McuEyeEar\SmaMcuEyeEar;
use App\Models\Mcu;
use App\Services\EyeEarDataTransformer;
use Illuminate\Support\Facades\Log;

class EyeEarRepository
{
    public function __construct(
        private EyeEarDataTransformer $transformer
    ) {}

    public function saveEyeEar(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw eye ear data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for eye ear', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            $eyeEarData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed eye ear data to save', ['level' => $groupLevel, 'data' => $eyeEarData]);

            // Check if there's any meaningful data
            if ($this->isEmptyData($eyeEarData, $groupLevel)) {
                Log::warning('Skipping eye ear save - no meaningful data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $eyeEarData);

            Log::info('Eye ear data saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving eye ear', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data mata dan telinga: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save eye ear', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data mata dan telinga: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'dctk':
                DctkMcuEyeEar::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sd':
                SdMcuEyeEar::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'smp':
                SmpMcuEyeEar::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuEyeEar::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            default:
                throw new \Exception("Unknown group level: {$groupLevel}");
        }
    }

    private function isEmptyData(array $data, string $groupLevel): bool
    {
        unset($data['mcu_id']);

        $hasAnyValue = false;
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $hasAnyValue = true;
                break;
            }
        }

        return !$hasAnyValue;
    }
}