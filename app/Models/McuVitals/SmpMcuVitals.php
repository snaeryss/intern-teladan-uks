<?php

namespace App\Models\McuVitals;

use App\Enums\MCU\MurmurEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmpMcuVitals extends Model
{
    use HasFactory;

    protected $table = 'mcu_smp_vitals';

    protected $fillable = [
        'mcu_id',
        'systolic_blood_pressure',
        'diastolic_blood_pressure',
        'heart_rate',
        'respiratory_rate',
        'temperature',
        'heart_murmur',
        'lung_murmur',
    ];

    protected $casts = [
        'systolic_blood_pressure' => 'integer',
        'diastolic_blood_pressure' => 'integer',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
        'temperature' => 'decimal:1',
        'heart_murmur' => MurmurEnum::class,
        'lung_murmur' => MurmurEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function getBloodPressureAttribute(): string
    {
        if (!$this->systolic_blood_pressure || !$this->diastolic_blood_pressure) {
            return '-';
        }
        return "{$this->systolic_blood_pressure}/{$this->diastolic_blood_pressure} mmHg";
    }

    public function getHeartMurmurLabelAttribute(): string
    {
        return $this->heart_murmur?->label() ?? 'Tidak';
    }

    public function getLungMurmurLabelAttribute(): string
    {
        return $this->lung_murmur?->label() ?? 'Tidak';
    }

    public function hasAnyMurmur(): bool
    {
        return $this->heart_murmur?->hasMurmur() || $this->lung_murmur?->hasMurmur();
    }
}