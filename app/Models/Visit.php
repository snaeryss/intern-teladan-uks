<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'day',
        'date',
        'arrival_time',
        'departure_time',
        'complaint',
        'treatment',
        'outcome_notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
