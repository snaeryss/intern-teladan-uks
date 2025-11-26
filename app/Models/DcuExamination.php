<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DcuExamination extends Model
{
    use HasFactory;

    protected $table = 'dcu_examinations';

    protected $fillable = [
        'dcu_id',
        'occlusion',
        'mucosal_notes',
        'decayed_teeth',
        'missing_teeth',
        'filled_teeth',
        'brushing_frequency',
        'brushing_time',
        'uses_toothpaste',
        'consumes_sweets',
    ];

    protected $casts = [
        'decayed_teeth' => 'decimal:1',
        'missing_teeth' => 'decimal:1',
        'filled_teeth' => 'decimal:1',
    ];

    public function dcu(): BelongsTo
    {
        return $this->belongsTo(Dcu::class, 'dcu_id');
    }

    public function getDmfTotalAttribute(): float
    {
        return ($this->decayed_teeth ?? 0) + 
               ($this->missing_teeth ?? 0) + 
               ($this->filled_teeth ?? 0);
    }
}