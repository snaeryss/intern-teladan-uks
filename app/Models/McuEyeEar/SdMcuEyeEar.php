<?php

namespace App\Models\McuEyeEar;

use App\Enums\MCU\OuterEyeEnum;
use App\Enums\MCU\VisualAcuityEnum;
use App\Enums\MCU\YesNoEnum;
use App\Enums\MCU\OuterEarEnum;
use App\Enums\MCU\HearingAcuityEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdMcuEyeEar extends Model
{
    use HasFactory;

    protected $table = 'mcu_sd_eye_ear';

    protected $fillable = [
        'mcu_id',
        'outer_eye',
        'outer_eye_notes',
        'visual_acuity',
        'visual_acuity_notes',
        'glasses',
        'glasses_notes',
        'eye_infection',
        'eye_infection_notes',
        'other_eye_problems',
        'outer_ear',
        'outer_ear_notes',
        'earwax',
        'earwax_notes',
        'ear_infection',
        'ear_infection_notes',
        'hearing_acuity',
        'hearing_acuity_notes',
        'other_ear_problems',
    ];

    protected $casts = [
        'outer_eye' => OuterEyeEnum::class,
        'visual_acuity' => VisualAcuityEnum::class,
        'glasses' => YesNoEnum::class,
        'eye_infection' => YesNoEnum::class,
        'outer_ear' => OuterEarEnum::class,
        'earwax' => YesNoEnum::class,
        'ear_infection' => YesNoEnum::class,
        'hearing_acuity' => HearingAcuityEnum::class,
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

    public function getGlassesLabelAttribute(): string
    {
        return $this->glasses?->label() ?? 'Tidak';
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

    public function getHearingAcuityLabelAttribute(): string
    {
        return $this->hearing_acuity?->label() ?? 'Normal';
    }

    public function hasEyeProblems(): bool
    {
        return $this->outer_eye?->value === 'unhealthy'
            || $this->visual_acuity?->value !== 'normal'
            || $this->eye_infection?->isYes()
            || !empty($this->other_eye_problems);
    }

    public function hasEarProblems(): bool
    {
        return $this->outer_ear?->value === 'unhealthy'
            || $this->earwax?->isYes()
            || $this->ear_infection?->isYes()
            || $this->hearing_acuity?->value === 'impaired'
            || !empty($this->other_ear_problems);
    }
}