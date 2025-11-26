<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EyeEarDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $transformed = ['mcu_id' => $mcuId];

        if (in_array($groupLevel, ['dctk', 'sd'])) {
            $transformed = array_merge($transformed, $this->transformDctkSd($data));
        } elseif (in_array($groupLevel, ['smp', 'sma'])) {
            $transformed = array_merge($transformed, $this->transformSmpSma($data));
        }

        Log::info('Eye ear data transformed', [
            'group_level' => $groupLevel,
            'input_keys' => array_keys($data),
            'output' => $transformed
        ]);

        return $transformed;
    }

    private function transformDctkSd(array $data): array
    {
        return [
            // Eye fields
            'outer_eye' => $this->normalizeOuterEye($data['mata_luar'] ?? $data['outer_eye'] ?? 'normal'),
            'outer_eye_notes' => $data['mata_luar_ket'] ?? $data['outer_eye_notes'] ?? null,
            'visual_acuity' => $this->normalizeVisualAcuity($data['tajam_penglihatan'] ?? $data['visual_acuity'] ?? 'normal'),
            'visual_acuity_notes' => $data['tajam_penglihatan_ket'] ?? $data['visual_acuity_notes'] ?? null,
            'glasses' => $this->normalizeYesNo($data['kacamata'] ?? $data['glasses'] ?? 'tidak'),
            'glasses_notes' => $data['kacamata_ket'] ?? $data['glasses_notes'] ?? null,
            'eye_infection' => $this->normalizeYesNo($data['infeksi_mata'] ?? $data['eye_infection'] ?? 'tidak'),
            'eye_infection_notes' => $data['infeksi_mata_ket'] ?? $data['eye_infection_notes'] ?? null,
            'other_eye_problems' => $data['penglihatan_lainnya'] ?? $data['other_eye_problems'] ?? null,
            
            // Ear fields
            'outer_ear' => $this->normalizeOuterEar($data['telinga_luar'] ?? $data['outer_ear'] ?? 'sehat'),
            'outer_ear_notes' => $data['telinga_luar_ket'] ?? $data['outer_ear_notes'] ?? null,
            'earwax' => $this->normalizeYesNo($data['serumen'] ?? $data['earwax'] ?? 'tidak'),
            'earwax_notes' => $data['serumen_ket'] ?? $data['earwax_notes'] ?? null,
            'ear_infection' => $this->normalizeYesNo($data['infeksi_telinga'] ?? $data['ear_infection'] ?? 'tidak'),
            'ear_infection_notes' => $data['infeksi_telinga_ket'] ?? $data['ear_infection_notes'] ?? null,
            'hearing_acuity' => $this->normalizeHearingAcuity($data['tajam_pendengaran'] ?? $data['hearing_acuity'] ?? 'normal'),
            'hearing_acuity_notes' => $data['tajam_pendengaran_ket'] ?? $data['hearing_acuity_notes'] ?? null,
            'other_ear_problems' => $data['pendengaran_lainnya'] ?? $data['other_ear_problems'] ?? null,
        ];
    }

    private function transformSmpSma(array $data): array
    {
        return [
            // Eye fields
            'outer_eye' => $this->normalizeOuterEye($data['mata_luar'] ?? $data['outer_eye'] ?? 'normal'),
            'visual_acuity' => $this->normalizeVisualAcuity($data['tajam_penglihatan'] ?? $data['visual_acuity'] ?? 'normal'),
            'visual_acuity_notes' => $data['tajam_penglihatan_ket'] ?? $data['visual_acuity_notes'] ?? null,
            'color_blindness' => $this->normalizeYesNo($data['buta_warna'] ?? $data['color_blindness'] ?? 'tidak'),
            'eye_infection' => $this->normalizeYesNo($data['infeksi_mata'] ?? $data['eye_infection'] ?? 'tidak'),
            
            // Ear fields
            'outer_ear' => $this->normalizeOuterEar($data['telinga_luar'] ?? $data['outer_ear'] ?? 'sehat'),
            'earwax' => $this->normalizeYesNo($data['serumen'] ?? $data['earwax'] ?? 'tidak'),
            'ear_infection' => $this->normalizeYesNo($data['infeksi_telinga'] ?? $data['ear_infection'] ?? 'tidak'),
            'other_ear_problems' => $data['pendengaran_lainnya'] ?? $data['other_ear_problems'] ?? null,
        ];
    }

    private function normalizeOuterEye($value): string
    {
        if (is_null($value)) {
            return 'normal';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'tidak', 'tidak sehat', 'unhealthy' => 'unhealthy',
            'normal' => 'normal',
            default => 'normal',
        };
    }

    private function normalizeVisualAcuity($value): string
    {
        if (is_null($value)) {
            return 'normal';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'lowvision', 'low_vision', 'low vision' => 'low_vision',
            'kebutaan', 'blindness' => 'blindness',
            'kelainan_refraksi', 'kelainan refraksi', 'refractive_disorder' => 'refractive_disorder',
            'normal' => 'normal',
            default => 'normal',
        };
    }

    private function normalizeOuterEar($value): string
    {
        if (is_null($value)) {
            return 'healthy';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'tidak', 'tidak sehat', 'unhealthy' => 'unhealthy',
            'sehat', 'healthy' => 'healthy',
            default => 'healthy',
        };
    }

    private function normalizeHearingAcuity($value): string
    {
        if (is_null($value)) {
            return 'normal';
        }

        $normalized = strtolower(trim($value));
        
        return match($normalized) {
            'gangguan', 'ada gangguan', 'impaired' => 'impaired',
            'normal' => 'normal',
            default => 'normal',
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
}