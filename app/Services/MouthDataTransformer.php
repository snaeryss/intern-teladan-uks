<?php

namespace App\Services;

use App\Enums\MCU\YesNoEnum;
use Illuminate\Support\Facades\Log;

class MouthDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = [];

        if (in_array($groupLevel, ['dctk', 'sd'])) {
            $transformed = $this->transformDctkSd($data);
        } elseif (in_array($groupLevel, ['smp', 'sma'])) {
            $transformed = $this->transformSmpSma($data);
        }

        $transformed['mcu_id'] = $mcuId;

        Log::info('Mouth data transformed', [
            'group_level' => $groupLevel,
            'input_keys' => array_keys($data),
            'output' => $transformed
        ]);

        return $transformed;
    }

    private function transformDctkSd(array $data): array
    {
        return [
            // Rongga Mulut
            'oral_cleft' => $this->normalizeYesNo($data['celah_bibir'] ?? 'no'),
            'angular_cheilitis' => $this->normalizeYesNo($data['luka_sudut_mulut'] ?? 'no'),
            'stomatitis' => $this->normalizeYesNo($data['sariawan'] ?? 'no'),
            'coated_tongue' => $this->normalizeYesNo($data['lidah_kotor'] ?? 'no'),
            'other_lesions' => $this->normalizeYesNo($data['luka_lainnya'] ?? 'no'),
            'other_mouth_problems' => $data['mulut_lainnya'] ?? null,
            // Gigi & Gusi
            'caries' => $this->normalizeYesNo($data['caries'] ?? 'no'),
            'caries_notes' => $data['caries_ket'] ?? null,
            'misaligned_teeth' => $this->normalizeYesNo($data['gigi_depan'] ?? 'no'),
            'other_teeth_problems' => $data['gigi_lainnya'] ?? null,
        ];
    }

    private function transformSmpSma(array $data): array
    {
        return [
            // Rongga Mulut
            'oral_cleft' => $this->normalizeYesNo($data['celah_bibir'] ?? 'no'),
            'angular_cheilitis' => $this->normalizeYesNo($data['luka_sudut_mulut'] ?? 'no'),
            'stomatitis' => $this->normalizeYesNo($data['sariawan'] ?? 'no'),
            'coated_tongue' => $this->normalizeYesNo($data['lidah_kotor'] ?? 'no'),
            'other_lesions' => $this->normalizeYesNo($data['luka_lainnya'] ?? 'no'),
            'other_mouth_problems' => $data['mulut_lainnya'] ?? null,
        ];
    }

    private function normalizeYesNo($value): string
    {
        if (is_null($value)) {
            return 'no';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'ya', 'yes' => 'yes',
            'tidak', 'no' => 'no',
            default => 'no',
        };
    }
}