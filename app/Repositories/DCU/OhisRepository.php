<?php

namespace App\Repositories\DCU;

use App\Models\DcuOhis;
use Illuminate\Support\Facades\Log;

class OhisRepository
{
    public function save(int $dcuId, array $data): void
    {
        try {
            $ohisData = [
                'dcu_id' => $dcuId,
                'notes' => $data['ohis_keterangan'] ?? null,
            ];

            if (!empty($data['di_matrix']) && is_array($data['di_matrix'])) {
                $di_matrix = $data['di_matrix'];

                $ohisData['di_1_1'] = (float) ($di_matrix[0][0] ?? 0);
                $ohisData['di_1_2'] = (float) ($di_matrix[0][1] ?? 0);
                $ohisData['di_1_3'] = (float) ($di_matrix[0][2] ?? 0);

                $ohisData['di_2_1'] = (float) ($di_matrix[1][0] ?? 0);
                $ohisData['di_2_2'] = (float) ($di_matrix[1][1] ?? 0);
                $ohisData['di_2_3'] = (float) ($di_matrix[1][2] ?? 0);
            } else {
                $ohisData['di_1_1'] = 0;
                $ohisData['di_1_2'] = 0;
                $ohisData['di_1_3'] = 0;
                $ohisData['di_2_1'] = 0;
                $ohisData['di_2_2'] = 0;
                $ohisData['di_2_3'] = 0;
            }

            if (!empty($data['ci_matrix']) && is_array($data['ci_matrix'])) {
                $ci_matrix = $data['ci_matrix'];

                $ohisData['ci_1_1'] = (float) ($ci_matrix[0][0] ?? 0);
                $ohisData['ci_1_2'] = (float) ($ci_matrix[0][1] ?? 0);
                $ohisData['ci_1_3'] = (float) ($ci_matrix[0][2] ?? 0);

                $ohisData['ci_2_1'] = (float) ($ci_matrix[1][0] ?? 0);
                $ohisData['ci_2_2'] = (float) ($ci_matrix[1][1] ?? 0);
                $ohisData['ci_2_3'] = (float) ($ci_matrix[1][2] ?? 0);
            } else {
                $ohisData['ci_1_1'] = 0;
                $ohisData['ci_1_2'] = 0;
                $ohisData['ci_1_3'] = 0;
                $ohisData['ci_2_1'] = 0;
                $ohisData['ci_2_2'] = 0;
                $ohisData['ci_2_3'] = 0;
            }

            $ohis = DcuOhis::updateOrCreate(
                ['dcu_id' => $dcuId],
                $ohisData
            );

            Log::info('OHIS saved', [
                'dcu_id' => $dcuId,
                'di_score' => $ohis->di_score,
                'ci_score' => $ohis->ci_score,
                'ohis_score' => $ohis->ohis_score,
                'ohis_status' => $ohis->ohis_status,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to save OHIS', [
                'error' => $e->getMessage(),
                'dcu_id' => $dcuId,
            ]);
            throw $e;
        }
    }
}