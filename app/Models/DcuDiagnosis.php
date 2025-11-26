<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DcuDiagnosis extends Model
{
    use HasFactory;

    protected $table = 'dcu_diagnoses';

    protected $fillable = [
        'dcu_id',
        'tooth_number',
        'dental_diagnosis_id',
        'notes',
    ];

    public function dcu(): BelongsTo
    {
        return $this->belongsTo(Dcu::class, 'dcu_id');
    }

    public function dentalDiagnosis(): BelongsTo
    {
        return $this->belongsTo(DentalDiagnosis::class, 'dental_diagnosis_id');
    }
}