<?php

namespace App\Repositories\MCU;

use App\Models\McuGeneral\DctkMcuGeneral;
use App\Models\McuGeneral\SdMcuGeneral;
use App\Models\Mcu;
use App\Services\GeneralDataTransformer;
use Illuminate\Support\Facades\Log;

class GeneralRepository
{
    public function __construct(
        private GeneralDataTransformer $transformer
    ) {}

    public function saveGeneral(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw general data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for general', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            if (!in_array($groupLevel, ['dctk', 'sd'])) {
                Log::info('Skipping general save - not DCTK/SD level', ['mcu_id' => $mcuId, 'level' => $groupLevel]);
                return;
            }

            $generalData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed general data to save', ['level' => $groupLevel, 'data' => $generalData]);

            if ($this->isEmptyData($generalData)) {
                Log::warning('Skipping general save - no meaningful data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $generalData);

            Log::info('General data saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving general', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data pemeriksaan umum: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save general', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data pemeriksaan umum: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'dctk':
                DctkMcuGeneral::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sd':
                SdMcuGeneral::updateOrCreate(
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