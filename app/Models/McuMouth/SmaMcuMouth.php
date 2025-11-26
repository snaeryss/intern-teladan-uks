<?php

namespace App\Models\McuMouth;

use App\Enums\MCU\YesNoEnum;
use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmaMcuMouth extends Model
{
    use HasFactory;

    protected $table = 'mcu_sma_mouth';

    protected $fillable = [
        'mcu_id',
        'oral_cleft',
        'angular_cheilitis',
        'stomatitis',
        'coated_tongue',
        'other_lesions',
        'other_mouth_problems',
    ];

    protected $casts = [
        'oral_cleft' => YesNoEnum::class,
        'angular_cheilitis' => YesNoEnum::class,
        'stomatitis' => YesNoEnum::class,
        'coated_tongue' => YesNoEnum::class,
        'other_lesions' => YesNoEnum::class,
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }
}