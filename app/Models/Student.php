<?php

namespace App\Models;

use App\Enums\Student\Gender;
use App\Enums\Student\Level as EnumsStudentLevel;
use App\Enums\Student\Status as EnumsStudentStatus;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static find(mixed $id, array|string $columns = ['*'])
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static where($column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static whereRelation($relation, $column, mixed $operator = null, mixed $value = null)
 */
class Student extends Model
{
    use HasFactory, HasUuids;

    /**
     * @var string
     */
    protected $keyType = 'string';
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $casts = [
        'date_birth' => 'date',
        'status' => EnumsStudentStatus::class,
        'school_level' => EnumsStudentLevel::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nis',
        'name',
        'date_birth',
        'sex',
        'school_level',
        'group_year',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'date_birth_text',
        'class',
    ];

    /**
     * @return HasMany
     */
    public function studentClasses(): HasMany
    {
        return $this->hasMany(StudentClass::class, 'student_id', 'id');
    }

    /**
     * @return Attribute
     */
    protected function dateBirthText(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_birth->locale(app()->getLocale())->translatedFormat('d F Y'),
        );
    }

    /**
     * @return Attribute
     */
    protected function sex(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => Gender::tryFrom($value ?? Gender::UNKNOWN->value),
        );
    }

    protected function class(): Attribute
    {
        return Attribute::make(
            get: function() {
                $currentClass = $this->getCurrentClassAttribute();
                return $currentClass 
                    ? "{$currentClass->class_level} {$currentClass->class_name}" 
                    : '-';
            }
        );
    }

    /**
     * return current class by active academic year
     * @return StudentClass|null
     */
    public function getCurrentClassAttribute(): StudentClass | null
    {
        $active = AcademicYear::where('is_active', 1)->first();

        if (!$active) {
            return null;
        }
        $params['student_id'] = $this->id;
        $params['group_year'] = substr($active->year_start, -2);
        return StudentClass::where($params)->first();
    }

    /**
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(StudentAccount::class, 'student_id', 'id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}