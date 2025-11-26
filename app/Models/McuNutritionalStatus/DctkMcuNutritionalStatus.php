<?php

namespace App\Models\McuNutritionalStatus;

use App\Enums\MCU\AnemiaEnum;
use App\Enums\MCU\NutritionalStatusEnum;
use App\Enums\MCU\WeightForAgeEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DctkMcuNutritionalStatus extends Model
{
    use HasFactory;

    protected $table = 'dctk_mcu_nutritional_status';

    protected $fillable = [
        'mcu_id',
        'weight',
        'height',
        'head_circumference',
        'arm_circumference',
        'abdominal_circumference',
        'bmi',
        'nutritional_status',
        'weight_for_age',
        'anemia',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'arm_circumference' => 'decimal:2',
        'abdominal_circumference' => 'decimal:2',
        'bmi' => 'string',
        'nutritional_status' => NutritionalStatusEnum::class,
        'weight_for_age' => WeightForAgeEnum::class,
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

    public function getWeightForAgeLabelAttribute(): string
    {
        return $this->weight_for_age?->label() ?? 'Normal';
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