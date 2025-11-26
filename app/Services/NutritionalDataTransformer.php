<?php

namespace App\Services;

use App\Enums\MCU\AnemiaEnum;
use App\Enums\MCU\NutritionalStatusEnum;
use App\Enums\MCU\WeightForAgeEnum;
use App\Enums\MCU\HeightForAgeEnum;

class NutritionalDataTransformer
{
    public function transform(int $mcuId, array $data, string $groupLevel): array
    {
        $baseData = [
            'mcu_id' => $mcuId,
            'weight' => $this->toDecimal($data['weight'] ?? null),
            'height' => $this->toDecimal($data['height'] ?? null),
            'bmi' => $data['bmi'] ?? null,
            'nutritional_status' => $this->transformNutritionalStatus($data['nutritional_status'] ?? null),
            'anemia' => $this->transformAnemia($data['anemia'] ?? null),
        ];

        if (in_array($groupLevel, ['dctk', 'sd'])) {
            $baseData['head_circumference'] = $this->toDecimal($data['head_circumference'] ?? null);
            $baseData['arm_circumference'] = $this->toDecimal($data['arm_circumference'] ?? null);
            $baseData['abdominal_circumference'] = $this->toDecimal($data['abdominal_circumference'] ?? null);
            $baseData['weight_for_age'] = $this->transformWeightForAge($data['weight_for_age'] ?? $data['weight_height_age'] ?? null);
        } elseif (in_array($groupLevel, ['smp', 'sma'])) {
            $baseData['height_for_age'] = $this->transformHeightForAge($data['height_for_age'] ?? $data['weight_height_age'] ?? null);
        }

        return $baseData;
    }

    private function toDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    private function transformNutritionalStatus(?string $status): ?string
    {
        if ($status === null || $status === '') {
            return null;
        }
        
        $enum = NutritionalStatusEnum::fromLabel($status);
        
        if ($enum === null) {
            return null;
        }
        
        return $enum->value;
    }

    private function transformWeightForAge(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return 'normal'; // default
        }
        
        $enum = WeightForAgeEnum::fromLabel($value);
        
        if ($enum === null) {
            return 'normal';
        }
        
        return $enum->value;
    }

    private function transformHeightForAge(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return 'normal'; 
        }
        
        if ($value === 'pendek') {
            return 'pendek';
        }
        
        $enum = HeightForAgeEnum::fromLabel($value);
        
        if ($enum === null) {
            return 'normal';
        }
        
        return $enum->value;
    }

    private function transformAnemia(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return 'tidak';
        }
        
        $enum = AnemiaEnum::fromLabel($value);
        
        if ($enum === null) {
            return 'tidak';
        }
        
        return $enum->value; 
    }
}