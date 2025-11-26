<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class HygieneDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = [
            'mcu_id' => $mcuId,
            'hair' => $this->normalizeHealthyUnhealthy($data['hair'] ?? $data['rambut'] ?? 'sehat'),
            'skin_patches' => $this->normalizeHealthyUnhealthy($data['skin_patches'] ?? $data['kulit_bercak'] ?? 'sehat'),
            'skin_patches_notes' => $data['skin_patches_notes'] ?? $data['kulit_bercak_ket'] ?? null,
            'scaly_skin' => $this->normalizeYesNo($data['scaly_skin'] ?? $data['kulit_bersisik'] ?? 'tidak'),
            'bruised_skin' => $this->normalizeYesNo($data['bruised_skin'] ?? $data['kulit_memar'] ?? 'tidak'),
            'cut_skin' => $this->normalizeYesNo($data['cut_skin'] ?? $data['kulit_sayatan'] ?? 'tidak'),
            'sores' => $this->normalizeYesNo($data['sores'] ?? $data['kulit_koreng'] ?? 'tidak'),
            'hard_to_heal_sores' => $this->normalizeYesNo($data['hard_to_heal_sores'] ?? $data['luka_koreng_sukar'] ?? 'tidak'),
            'injection_marks' => $this->normalizeYesNo($data['injection_marks'] ?? $data['kulit_suntikan'] ?? 'tidak'),
            'nails' => $this->normalizeNails($data['nails'] ?? $data['kuku'] ?? 'sehat'),
        ];

        Log::info('Hygiene data transformed', [
            'group_level' => $groupLevel,
            'input_keys' => array_keys($data),
            'output' => $transformed
        ]);

        return $transformed;
    }

    private function normalizeHealthyUnhealthy($value): string
    {
        if (is_null($value)) {
            return 'healthy';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'unhealthy', 'tidak', 'tidak sehat', 'kotor' => 'unhealthy',
            'healthy', 'sehat', 'bersih' => 'healthy',
            default => 'healthy',
        };
    }

    private function normalizeYesNo($value): string
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

    private function normalizeNails($value): string
    {
        if (is_null($value)) {
            return 'healthy';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'dirty', 'kotor', 'kotor/panjang' => 'dirty',
            'healthy', 'sehat', 'bersih', 'sehat/bersih' => 'healthy',
            default => 'healthy',
        };
    }
}