<?php

namespace App\Services;

use App\Models\BmiReferenceMale;
use App\Models\BmiReferenceFemale;
use App\Models\Student;
use Carbon\Carbon;

class BmiEvaluationService
{
    /**
     * Evaluasi status gizi berdasarkan BB, TB, dan data siswa
     */
    public function evaluate(string|int $studentId, float $weight, float $height): array
    {
        $student = Student::findOrFail($studentId);
        $age = $this->calculateAge($student->date_birth);
        $bmi = $this->calculateBmi($weight, $height);
        $reference = $this->getBmiReference($student->sex->value, $age['years'], $age['months']);
        
        if (!$reference) {
            return [
                'success' => false,
                'message' => "Data referensi BMI tidak ditemukan untuk usia {$age['years']} tahun {$age['months']} bulan",
                'bmi' => $bmi,
                'age' => $age,
            ];
        }

        $status = $this->determineNutritionalStatus($bmi, $reference);
        
        return [
            'success' => true,
            'bmi' => $bmi,
            'nutritional_status' => $status,
            'nutritional_status_label' => $this->getStatusLabel($status),
            'age' => $age,
            'reference' => [
                'very_thin_max' => (float) $reference->very_thin_max,
                'thin_max' => (float) $reference->thin_max,
                'lower_normal' => (float) $reference->lower_normal,
                'ideal' => (float) $reference->ideal,
                'upper_normal' => (float) $reference->upper_normal,
                'overweight_max' => (float) $reference->overweight_max,
                'very_overweight' => (float) $reference->very_overweight,
            ],
        ];
    }

    /**
     * Calculate age (years and months)
     */
    private function calculateAge($birthDate): array
    {
        $birth = Carbon::parse($birthDate);
        $now = Carbon::now();
        
        $years = $birth->diffInYears($now);
        $months = $birth->copy()->addYears($years)->diffInMonths($now);
        
        return [
            'years' => $years,
            'months' => $months,
            'total_months' => ($years * 12) + $months,
        ];
    }

    /**
     * Calculate BMI
     */
    private function calculateBmi(float $weight, float $height): float
    {
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        
        return round($bmi, 2);
    }

    /**
     * Get BMI reference from database (gender and age)
     */
    private function getBmiReference(string $gender, int $years, int $months)
    {
        if ($gender === 'L') {
            return BmiReferenceMale::byAge($years, $months)->first();
        } else {
            return BmiReferenceFemale::byAge($years, $months)->first();
        }
    }

    /**
     * Determine nutritional status
     */
    private function determineNutritionalStatus(float $bmi, $reference): string
    {
        if ($bmi <= $reference->very_thin_max) {
            return 'very_thin';
        } elseif ($bmi <= $reference->thin_max) {
            return 'thin';
        } elseif ($bmi <= $reference->upper_normal) {
            return 'normal';
        } elseif ($bmi <= $reference->overweight_max) {
            return 'overweight';
        } else {
            return 'very_overweight';
        }
    }

    /**
     * Get Indonesian label for nutritional status
     */
    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'very_thin' => 'Sangat Kurus',
            'thin' => 'Kurus',
            'normal' => 'Normal',
            'overweight' => 'Gemuk',
            'very_overweight' => 'Sangat Gemuk (Obesitas)',
            default => '-',
        };
    }
}