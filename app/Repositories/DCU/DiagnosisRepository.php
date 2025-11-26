<?php

namespace App\Repositories\DCU;

use App\Models\DcuDiagnosis;
use Illuminate\Support\Facades\Log;

class DiagnosisRepository
{
    public function save(int $dcuId, array $diagnoses): void
    {
        try {
            DcuDiagnosis::where('dcu_id', $dcuId)->delete();
            
            Log::info('DiagnosisRepository: Deleted old diagnoses', ['dcu_id' => $dcuId]);
            
            $insertData = [];
            foreach ($diagnoses as $diagnosis) {
                if (isset($diagnosis['tooth_number']) && isset($diagnosis['diagnosis_id'])) {
                    $insertData[] = [
                        'dcu_id' => $dcuId,
                        'tooth_number' => $diagnosis['tooth_number'],
                        'dental_diagnosis_id' => $diagnosis['diagnosis_id'],
                        'notes' => $diagnosis['notes'] ?? '-',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($insertData)) {
                DcuDiagnosis::insert($insertData);
                Log::info('DiagnosisRepository: Inserted diagnoses', [
                    'dcu_id' => $dcuId,
                    'count' => count($insertData)
                ]);
            } else {
                Log::info('DiagnosisRepository: No diagnoses to insert', ['dcu_id' => $dcuId]);
            }

        } catch (\Exception $e) {
            Log::error('DiagnosisRepository: Failed to save diagnoses', [
                'dcu_id' => $dcuId,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function delete(int $diagnosisId): bool
    {
        try {
            $diagnosis = DcuDiagnosis::findOrFail($diagnosisId);
            $diagnosis->delete();
            
            Log::info('DiagnosisRepository: Diagnosis deleted', ['id' => $diagnosisId]);
            return true;
        } catch (\Exception $e) {
            Log::error('DiagnosisRepository: Failed to delete diagnosis', [
                'id' => $diagnosisId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}