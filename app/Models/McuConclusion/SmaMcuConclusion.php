<?php

namespace App\Models\McuConclusion;

use App\Models\Mcu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmaMcuConclusion extends Model
{
    use HasFactory;

    protected $table = 'mcu_sma_conclusion';

    protected $fillable = [
        'mcu_id',
        'diagnosis',
        'treatment',
        'notes',
    ];

    public function mcu(): BelongsTo
    {
        return $this->belongsTo(Mcu::class, 'mcu_id', 'mcu_id');
    }

    public function hasDiagnosis(): bool
    {
        return !empty($this->diagnosis);
    }

    public function hasTreatment(): bool
    {
        return !empty($this->treatment);
    }

    public function hasNotes(): bool
    {
        return !empty($this->notes);
    }
}