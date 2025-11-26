<?php

namespace App\Services;

use App\Enums\MCU\HygieneStatusEnum;
use App\Enums\MCU\NailsStatusEnum;
use App\Enums\MCU\YesNoEnum;
use Illuminate\Support\Facades\Log;

class GeneralDataTransformer
{
    /**
     * Transform data pemeriksaan umum dari request ke format database.
     * Hanya berlaku untuk DCTK dan SD.
     */
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = [
            'mcu_id' => $mcuId,

            // Head
            'eyes_hygiene' => $this->normalizeHygiene($data['mata'] ?? 'healthy'),
            'eyes_hygiene_notes' => $data['mata_ket'] ?? null,
            'nose_hygiene' => $this->normalizeHygiene($data['hidung'] ?? 'healthy'),
            'nose_hygiene_notes' => $data['hidung_ket'] ?? null,
            'oral_cavity' => $this->normalizeYesNo($data['mulut'] ?? 'no'),
            'oral_cavity_notes' => $data['mulut_ket'] ?? null,

            // Thorax
            'heart' => $this->normalizeYesNo($data['jantung'] ?? 'no'),
            'heart_notes' => $data['jantung_ket'] ?? null,
            'lungs' => $this->normalizeYesNo($data['paru'] ?? 'no'),
            'lungs_notes' => $data['paru_ket'] ?? null,
            'neurology' => $this->normalizeYesNo($data['neurologi'] ?? 'no'),
            'neurology_notes' => $data['neurologi_ket'] ?? null,

            // Hygiene - SUPPORT BOTH: general_rambut OR rambut
            'hair' => $this->normalizeYesNo($data['general_rambut'] ?? $data['rambut'] ?? 'no'),
            'hair_notes' => $data['general_rambut_ket'] ?? $data['rambut_ket'] ?? null,
            'skin' => $this->normalizeYesNo($data['general_kulit'] ?? $data['kulit'] ?? 'no'),
            'skin_notes' => $data['general_kulit_ket'] ?? $data['kulit_ket'] ?? null,
            'nails' => $this->normalizeNails($data['general_kuku'] ?? $data['kuku'] ?? 'healthy'),
            'nails_notes' => $data['general_kuku_ket'] ?? $data['kuku_ket'] ?? null,
        ];

        Log::info('General data transformed', [
            'group_level' => $groupLevel,
            'input' => $data,
            'output' => $transformed
        ]);

        return $transformed;
    }

    /**
     * Normalisasi nilai 'sehat'/'tidak' ke enum HygieneStatusEnum
     */
    private function normalizeHygiene($value): string
    {
        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'unhealthy', 'tidak', 'tidak sehat', 'kotor' => HygieneStatusEnum::UNHEALTHY->value,
            'healthy', 'sehat', 'bersih' => HygieneStatusEnum::HEALTHY->value,
            default => HygieneStatusEnum::HEALTHY->value,
        };
    }

    /**
     * Normalisasi nilai 'ya'/'tidak' ke enum YesNoEnum
     */
    private function normalizeYesNo($value): string
    {
        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'yes', 'ya' => YesNoEnum::YES->value,
            'no', 'tidak' => YesNoEnum::NO->value,
            default => YesNoEnum::NO->value,
        };
    }

    /**
     * Normalisasi nilai 'sehat'/'kotor' ke enum NailsStatusEnum
     */
    private function normalizeNails($value): string
    {
        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'dirty', 'kotor', 'kotor/panjang' => NailsStatusEnum::DIRTY->value,
            'healthy', 'sehat', 'sehat/bersih' => NailsStatusEnum::HEALTHY->value,
            default => NailsStatusEnum::HEALTHY->value,
        };
    }
}