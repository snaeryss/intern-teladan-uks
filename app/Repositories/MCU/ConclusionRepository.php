<?php

namespace App\Repositories\MCU;

use App\Models\McuConclusion\DctkMcuConclusion;
use App\Models\McuConclusion\SdMcuConclusion;
use App\Models\McuConclusion\SmpMcuConclusion;
use App\Models\McuConclusion\SmaMcuConclusion;
use App\Models\Mcu;
use App\Services\ConclusionDataTransformer;
use Illuminate\Support\Facades\Log;

class ConclusionRepository
{
    public function __construct(
        private ConclusionDataTransformer $transformer
    ) {}

    public function saveConclusion(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw conclusion data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for conclusion', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            $conclusionData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed conclusion data to save', ['level' => $groupLevel, 'data' => $conclusionData]);

            if ($this->isEmptyData($conclusionData)) {
                Log::warning('Skipping conclusion save - no meaningful data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $conclusionData);

            Log::info('Conclusion data saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving conclusion', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan kesimpulan: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save conclusion', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses kesimpulan: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'dctk':
                DctkMcuConclusion::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sd':
                SdMcuConclusion::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'smp':
                SmpMcuConclusion::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuConclusion::updateOrCreate(
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

        // Check if all text fields are empty
        foreach ($data as $key => $value) {
            if (!empty($value) && $value !== '-') {
                return false;
            }
        }

        return true;
    }
}