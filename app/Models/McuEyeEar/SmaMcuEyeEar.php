<?php

namespace App\Models\McuEyeEar;

use App\Enums\MCU\OuterEyeEnum;
use App\Enums\MCU\VisualAcuityEnum;
use App\Enums\MCU\YesNoEnum;
use App\Enums\MCU\OuterEarEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmaMcuEyeEar extends Model
{
    use HasFactory;

    protected $table = 'mcu_sma_eye_ear';

    protected $fillable = [
        'mcu_id',
        'outer_eye',
        'visual_acuity',
        'visual_acuity_notes',
        'color_blindness',
        'eye_infection',
        'outer_ear',
        'earwax',
        'ear_infection',
        'other_ear_problems',
    ];

    protected $casts = [
        'outer_eye' => OuterEyeEnum::class,
        'visual_acuity' => VisualAcuityEnum::class,
        'color_blindness' => YesNoEnum::class,
        'eye_infection' => YesNoEnum::class,
        'outer_ear' => OuterEarEnum::class,
        'earwax' => YesNoEnum::class,
        'ear_infection' => YesNoEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function getOuterEyeLabelAttribute(): string
    {
        return $this->outer_eye?->label() ?? 'Normal';
    }

    public function getVisualAcuityLabelAttribute(): string
    {
        return $this->visual_acuity?->label() ?? 'Normal';
    }

    public function getColorBlindnessLabelAttribute(): string
    {
        return $this->color_blindness?->label() ?? 'Tidak';
    }

    public function getEyeInfectionLabelAttribute(): string
    {
        return $this->eye_infection?->label() ?? 'Tidak';
    }

    public function getOuterEarLabelAttribute(): string
    {
        return $this->outer_ear?->label() ?? 'Sehat';
    }

    public function getEarwaxLabelAttribute(): string
    {
        return $this->earwax?->label() ?? 'Tidak';
    }

    public function getEarInfectionLabelAttribute(): string
    {
        return $this->ear_infection?->label() ?? 'Tidak';
    }

    public function hasEyeProblems(): bool
    {
        return $this->outer_eye?->value === 'unhealthy'
            || $this->visual_acuity?->value !== 'normal'
            || $this->color_blindness?->isYes()
            || $this->eye_infection?->isYes();
    }

    public function hasEarProblems(): bool
    {
        return $this->outer_ear?->value === 'unhealthy'
            || $this->earwax?->isYes()
            || $this->ear_infection?->isYes()
            || !empty($this->other_ear_problems);
    }
}