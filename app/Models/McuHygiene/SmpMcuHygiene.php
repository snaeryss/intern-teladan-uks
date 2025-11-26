<?php

namespace App\Models\McuHygiene;

use App\Enums\MCU\HairSkinStatusEnum;
use App\Enums\MCU\NailsStatusEnum;
use App\Enums\MCU\YesNoEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmpMcuHygiene extends Model
{
    use HasFactory;

    protected $table = 'mcu_smp_hygiene';

    protected $fillable = [
        'mcu_id',
        'hair',
        'skin_patches',
        'skin_patches_notes',
        'scaly_skin',
        'bruised_skin',
        'cut_skin',
        'sores',
        'hard_to_heal_sores',
        'injection_marks',
        'nails',
    ];

    protected $casts = [
        'hair' => HairSkinStatusEnum::class,
        'skin_patches' => HairSkinStatusEnum::class,
        'scaly_skin' => YesNoEnum::class,
        'bruised_skin' => YesNoEnum::class,
        'cut_skin' => YesNoEnum::class,
        'sores' => YesNoEnum::class,
        'hard_to_heal_sores' => YesNoEnum::class,
        'injection_marks' => YesNoEnum::class,
        'nails' => NailsStatusEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function getHairLabelAttribute(): string
    {
        return $this->hair?->label() ?? 'Sehat/Bersih';
    }

    public function getSkinPatchesLabelAttribute(): string
    {
        return $this->skin_patches?->label() ?? 'Sehat/Bersih';
    }

    public function getScalySkinLabelAttribute(): string
    {
        return $this->scaly_skin?->label() ?? 'Tidak';
    }

    public function getBruisedSkinLabelAttribute(): string
    {
        return $this->bruised_skin?->label() ?? 'Tidak';
    }

    public function getCutSkinLabelAttribute(): string
    {
        return $this->cut_skin?->label() ?? 'Tidak';
    }

    public function getSoresLabelAttribute(): string
    {
        return $this->sores?->label() ?? 'Tidak';
    }

    public function getHardToHealSoresLabelAttribute(): string
    {
        return $this->hard_to_heal_sores?->label() ?? 'Tidak';
    }

    public function getInjectionMarksLabelAttribute(): string
    {
        return $this->injection_marks?->label() ?? 'Tidak';
    }

    public function getNailsLabelAttribute(): string
    {
        return $this->nails?->label() ?? 'Sehat/Bersih';
    }

    public function hasHygieneProblems(): bool
    {
        return !$this->hair?->isHealthy()
            || !$this->skin_patches?->isHealthy()
            || $this->scaly_skin?->isYes()
            || $this->bruised_skin?->isYes()
            || $this->cut_skin?->isYes()
            || $this->sores?->isYes()
            || $this->hard_to_heal_sores?->isYes()
            || $this->injection_marks?->isYes()
            || $this->nails?->value === 'dirty';
    }

    public function hasSkinProblems(): bool
    {
        return !$this->skin_patches?->isHealthy()
            || $this->scaly_skin?->isYes()
            || $this->bruised_skin?->isYes()
            || $this->cut_skin?->isYes()
            || $this->sores?->isYes()
            || $this->hard_to_heal_sores?->isYes()
            || $this->injection_marks?->isYes();
    }
}