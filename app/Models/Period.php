<?php

namespace App\Models;

use App\Enums\CheckUpTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'month',
        'year',
        'is_active',
        'academic_year_id',
    ];

    protected $appends = ['display_name', 'type'];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function mcus(): HasMany
    {
        return $this->hasMany(Mcu::class, 'period_id');
    }

    public function dcus(): HasMany
    {
        return $this->hasMany(Dcu::class, 'period_id');
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $enum = CheckUpTypeEnum::tryFrom(Str::upper($this->name));
                $activityName = $enum ? $enum->label() : Str::upper($this->name);
                return "{$activityName} - {$this->month} {$this->year}";
            },
        );
    }

    // Accessor untuk mendapatkan type dari name
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::upper($this->name),
        );
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('name', 'LIKE', '%' . strtolower($type) . '%');
    }

    public function scopeByAcademicYear($query, int $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }
}