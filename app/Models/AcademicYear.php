<?php

namespace App\Models;

use App\Enums\AcademicYearStatusEnum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static orderBy($column, string $direction = 'asc')
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static active()
 */
class AcademicYear extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year_start',
        'year_end',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'is_active' => AcademicYearStatusEnum::class,
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class, 'academic_year_id');
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where('is_active', AcademicYearStatusEnum::ACTIVE);
    }

}
