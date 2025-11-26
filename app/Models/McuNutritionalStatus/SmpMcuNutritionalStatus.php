<?php

namespace App\Models\McuNutritionalStatus;

use App\Enums\MCU\AnemiaEnum;
use App\Enums\MCU\NutritionalStatusEnum;
use App\Enums\MCU\HeightForAgeEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmpMcuNutritionalStatus extends Model
{
    use HasFactory;

    protected $table = 'smp_mcu_nutritional_status';

    protected $fillable = [
        'mcu_id',
        'weight',
        'height',
        'bmi',
        'nutritional_status',
        'height_for_age',
        'anemia',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'string',
        'nutritional_status' => NutritionalStatusEnum::class,
        'height_for_age' => HeightForAgeEnum::class,
        'anemia' => AnemiaEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function getNutritionalStatusLabelAttribute(): string
    {
        return $this->nutritional_status?->label() ?? '-';
    }

    public function getHeightForAgeLabelAttribute(): string
    {
        return $this->height_for_age?->label() ?? 'Normal';
    }

    public function getAnemiaLabelAttribute(): string
    {
        return $this->anemia?->label() ?? 'Tidak';
    }

    public function hasAnemia(): bool
    {
        return $this->anemia?->hasAnemia() ?? false;
    }
}