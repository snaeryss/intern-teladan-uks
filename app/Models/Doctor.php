<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Doctor extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['id', 'name', 'signature', 'is_active'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(DoctorAccount::class, 'doctor_id', 'id');
    }
}