<?php

namespace App\Services;

use App\Enums\MCU\MurmurEnum;
use Illuminate\Support\Facades\Log;

class VitalsDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = [
            'mcu_id' => $mcuId,
            'systolic_blood_pressure' => $data['systolic_blood_pressure'] ?? $data['tekanan_darah_sistolik'] ?? null,
            'diastolic_blood_pressure' => $data['diastolic_blood_pressure'] ?? $data['tekanan_darah_diastolik'] ?? null,
            'heart_rate' => $data['heart_rate'] ?? $data['denyut_nadi'] ?? null,
            'respiratory_rate' => $data['respiratory_rate'] ?? $data['frekuensi_nafas'] ?? null,
            'temperature' => $data['temperature'] ?? $data['suhu'] ?? null,
        ];

        $heartMurmur = $data['heart_murmur'] ?? $data['bising_jantung'] ?? 'no';
        $lungMurmur = $data['lung_murmur'] ?? $data['bising_paru'] ?? 'no';

        $transformed['heart_murmur'] = $this->normalizeEnumValue($heartMurmur);
        $transformed['lung_murmur'] = $this->normalizeEnumValue($lungMurmur);

        Log::info('Vitals data transformed', [
            'group_level' => $groupLevel,
            'input' => $data,
            'output' => $transformed
        ]);

        return $transformed;
    }

    private function normalizeEnumValue($value): string
    {
        if (is_null($value)) {
            return 'no';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'ya', 'yes', '1', 'true' => 'yes',
            'tidak', 'no', '0', 'false', '' => 'no',
            default => 'no',
        };
    }
}