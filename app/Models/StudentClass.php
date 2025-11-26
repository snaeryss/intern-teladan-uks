<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static find(mixed $id, array|string $columns = ['*'])
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static select(array|mixed $columns = ['*'])
 * @method static where($column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 */
class StudentClass extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'student_id',
		'class_level',
		'class_name',
		'group_year',
		'school_level',
	];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
