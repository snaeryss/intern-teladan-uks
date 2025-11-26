<?php

namespace App\Repositories\DCU;

use App\Models\DcuExamination;
use Illuminate\Support\Facades\Log;

class ExaminationRepository
{
    public function save(int $dcuId, array $data): void
    {
        try {
            $examinationData = [
                'occlusion' => $data['oklusi'] ?? null,
                'mucosal_notes' => $data['mukosa'] ?? null,
                'decayed_teeth' => $data['dmf_d'] ?? 0,
                'missing_teeth' => $data['dmf_m'] ?? 0,
                'filled_teeth' => $data['dmf_f'] ?? 0,
                'brushing_frequency' => $data['frekuensi_sikat'] ?? null,
                'brushing_time' => $data['waktu_sikat'] ?? null,
                'uses_toothpaste' => $data['pasta_gigi'] ?? null,
                'consumes_sweets' => $data['makanan_manis'] ?? null,
            ];

            Log::info('Examination data to save', $examinationData);

            DcuExamination::updateOrCreate(
                ['dcu_id' => $dcuId],
                $examinationData
            );

            Log::info('Examination saved successfully', ['dcu_id' => $dcuId]);

        } catch (\Exception $e) {
            Log::error('Failed to save examination', [
                'error' => $e->getMessage(),
                'dcu_id' => $dcuId
            ]);
            throw $e;
        }
    }
}