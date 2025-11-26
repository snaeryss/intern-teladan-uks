<?php

namespace App\Repositories\MCU;

use App\Models\McuMouth\DctkMcuMouth;
use App\Models\McuMouth\SdMcuMouth;
use App\Models\McuMouth\SmpMcuMouth;
use App\Models\McuMouth\SmaMcuMouth;
use App\Models\Mcu;
use App\Services\MouthDataTransformer; // (Akan dibuat di step 5)
use Illuminate\Support\Facades\Log;

class MouthRepository
{
    public function __construct(
        private MouthDataTransformer $transformer
    ) {}

    public function saveMouth(int $mcuId, array $data): void
    {
        try {
            Log::info('Raw mouth data received', $data);

            $mcu = Mcu::with('student')->find($mcuId);
            if (!$mcu || !$mcu->student) {
                throw new \Exception('MCU or Student not found');
            }

            $groupLevel = $mcu->student->school_level->getGroupLevel();
            Log::info('Student level detected for mouth', ['group_level' => $groupLevel, 'mcu_id' => $mcuId]);

            $mouthData = $this->transformer->transform($mcuId, $data, $groupLevel);

            Log::info('Transformed mouth data to save', ['level' => $groupLevel, 'data' => $mouthData]);

            if ($this->isEmptyData($mouthData)) {
                Log::warning('Skipping mouth save - no meaningful data', ['mcu_id' => $mcuId]);
                return;
            }

            $this->saveByLevel($groupLevel, $mcuId, $mouthData);

            Log::info('Mouth data saved successfully', ['mcu_id' => $mcuId, 'level' => $groupLevel]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error saving mouth', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mcu_id' => $mcuId,
                'data' => $data
            ]);
            throw new \Exception('Gagal menyimpan data pemeriksaan mulut: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to save mouth', [
                'error' => $e->getMessage(),
                'mcu_id' => $mcuId,
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw new \Exception('Gagal memproses data pemeriksaan mulut: ' . $e->getMessage());
        }
    }

    private function saveByLevel(string $groupLevel, int $mcuId, array $data): void
    {
        switch ($groupLevel) {
            case 'dctk':
                DctkMcuMouth::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sd':
                SdMcuMouth::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'smp':
                SmpMcuMouth::updateOrCreate(
                    ['mcu_id' => $mcuId],
                    $data
                );
                break;
            case 'sma':
                SmaMcuMouth::updateOrCreate(
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
            // Cek jika ada nilai 'ya' atau ada 'notes/ket' yang terisi
            if ($value === 'yes' || ($value !== null && $value !== '' && str_contains($key, 'notes'))) {
                $hasData = true;
                break;
            }
        }

        return !$hasData;
    }
}