<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalDiagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
    ];

    public function dcuDiagnoses()
    {
        return $this->hasMany(DcuDiagnosis::class, 'dental_diagnosis_id');
    }
}