<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckupPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'academic_year_id',
    ];

    /**
     * Mendefinisikan relasi bahwa satu CheckupPeriod dimiliki oleh satu AcademicYear.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
