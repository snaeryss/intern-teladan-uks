<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ConclusionDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = [
            'mcu_id' => $mcuId,
            'diagnosis' => $this->sanitizeText($data['diagnosis'] ?? $data['kesimpulan'] ?? null),
            'treatment' => $this->sanitizeText($data['treatment'] ?? $data['saran'] ?? null),
            'notes' => $this->sanitizeText($data['notes'] ?? $data['follow_up'] ?? $data['catatan'] ?? null),
        ];

        Log::info('Conclusion data transformed', [
            'group_level' => $groupLevel,
            'input_keys' => array_keys($data),
            'output' => $transformed
        ]);

        return $transformed;
    }

    private function sanitizeText(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }

        $text = trim($text);
        
        if (in_array(strtolower($text), ['-', 'tidak ada', 'none', 'n/a'])) {
            return null;
        }

        return $text;
    }
}