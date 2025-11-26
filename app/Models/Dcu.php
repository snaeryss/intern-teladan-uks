<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Dcu extends Model
{
    use HasFactory;
    protected $table = 'dcu';
    protected $fillable = [
        'code', 
        'date', 
        'is_finish',
        'student_id', 
        'period_id', 
        'doctor_id', 
        'examined_by_doctor_id',
        'location_id'
    ];

    protected $casts = [
        'date' => 'date',
        'is_finish' => 'boolean',
    ];

    public function getStudentAgeAttribute(): string
    {
        if (!$this->student || !$this->student->date_birth) {
            return '-';
        }

        $birthDate = \Carbon\Carbon::parse($this->student->date_birth);
        $examDate = \Carbon\Carbon::parse($this->date);

        return $birthDate->diff($examDate)->format('%y tahun %m bulan');
    }

    public function getStudentAgeYearsAttribute(): int
    {
        if (!$this->student || !$this->student->date_birth) {
            return 0;
        }

        return \Carbon\Carbon::parse($this->student->date_birth)
            ->diffInYears(\Carbon\Carbon::parse($this->date));
    }

    public function getStudentAgeMonthsAttribute(): int
    {
        if (!$this->student || !$this->student->date_birth) {
            return 0;
        }

        $birthDate = \Carbon\Carbon::parse($this->student->date_birth);
        $examDate = \Carbon\Carbon::parse($this->date);
        
        return $birthDate->diff($examDate)->m;
    }

    // protected function setDateAttribute($value)
    // {
    //     $this->attributes['date'] = \Carbon\Carbon::parse($value)->toDateString();
    // }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function examinedByDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examined_by_doctor_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(DcuDiagnosis::class, 'dcu_id');
    }

     public function examination(): HasOne
    {
        return $this->hasOne(DcuExamination::class, 'dcu_id');
    }

    public function ohis(): HasOne
    {
        return $this->hasOne(DcuOhis::class, 'dcu_id');
    }

    public function scopeActivePeriod($query)
    {
        return $query->whereHas('period.academicYear', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeFinished($query)
    {
        return $query->where('is_finish', true);
    }

    public function scopeUnfinished($query)
    {
        return $query->where('is_finish', false);
    }
}