<?php

namespace App\Models\McuGeneral;

use App\Enums\MCU\HygieneStatusEnum;
use App\Enums\MCU\NailsStatusEnum;
use App\Enums\MCU\YesNoEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdMcuGeneral extends Model
{
    use HasFactory;

    protected $table = 'mcu_sd_general';

    protected $fillable = [
        'mcu_id',
        'eyes_hygiene',
        'eyes_hygiene_notes',
        'nose_hygiene',
        'nose_hygiene_notes',
        'oral_cavity',
        'oral_cavity_notes',
        'heart',
        'heart_notes',
        'lungs',
        'lungs_notes',
        'neurology',
        'neurology_notes',
        'hair',
        'hair_notes',
        'skin',
        'skin_notes',
        'nails',
        'nails_notes',
    ];

    protected $casts = [
        'eyes_hygiene' => HygieneStatusEnum::class,
        'nose_hygiene' => HygieneStatusEnum::class,
        'oral_cavity' => YesNoEnum::class,
        'heart' => YesNoEnum::class,
        'lungs' => YesNoEnum::class,
        'neurology' => YesNoEnum::class,
        'hair' => YesNoEnum::class,
        'skin' => YesNoEnum::class,
        'nails' => NailsStatusEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function getEyesHygieneLabelAttribute(): string
    {
        return $this->eyes_hygiene?->label() ?? 'Sehat/Bersih';
    }

    public function getNoseHygieneLabelAttribute(): string
    {
        return $this->nose_hygiene?->label() ?? 'Sehat/Bersih';
    }

    public function getOralCavityLabelAttribute(): string
    {
        return $this->oral_cavity?->label() ?? 'Tidak';
    }

    public function getHeartLabelAttribute(): string
    {
        return $this->heart?->label() ?? 'Tidak';
    }

    public function getLungsLabelAttribute(): string
    {
        return $this->lungs?->label() ?? 'Tidak';
    }

    public function getNeurologyLabelAttribute(): string
    {
        return $this->neurology?->label() ?? 'Tidak';
    }

    public function getHairLabelAttribute(): string
    {
        return $this->hair?->label() ?? 'Tidak';
    }

    public function getSkinLabelAttribute(): string
    {
        return $this->skin?->label() ?? 'Tidak';
    }

    public function getNailsLabelAttribute(): string
    {
        return $this->nails?->label() ?? 'Sehat/Bersih';
    }

    public function hasHeadProblems(): bool
    {
        return $this->eyes_hygiene?->value === 'unhealthy'
            || $this->nose_hygiene?->value === 'unhealthy'
            || $this->oral_cavity?->isYes();
    }

    public function hasThoraxProblems(): bool
    {
        return $this->heart?->isYes()
            || $this->lungs?->isYes()
            || $this->neurology?->isYes();
    }

    public function hasHygieneProblems(): bool
    {
        return $this->hair?->isYes()
            || $this->skin?->isYes()
            || $this->nails?->value === 'dirty';
    }
}