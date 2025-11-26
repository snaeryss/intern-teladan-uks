<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmiReferenceFemale extends Model
{
    use HasFactory;

    protected $table = 'bmi_reference_female';

    protected $fillable = [
        'age_years',
        'age_months',
        'very_thin_max',
        'thin_max',
        'lower_normal',
        'ideal',
        'upper_normal',
        'overweight_max',
        'very_overweight',
    ];

    protected $casts = [
        'age_years' => 'integer',
        'age_months' => 'integer',
        'very_thin_max' => 'decimal:1',
        'thin_max' => 'decimal:1',
        'lower_normal' => 'decimal:1',
        'ideal' => 'decimal:1',
        'upper_normal' => 'decimal:1',
        'overweight_max' => 'decimal:1',
        'very_overweight' => 'decimal:1',
    ];

    public function scopeByAge($query, int $years, int $months)
    {
        return $query->where('age_years', $years)
                     ->where('age_months', $months);
    }
}