<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $attributes = [])
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static where($column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 */
class StudentSyncHistory extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
		'new',
		'skipped',
		'updated',
		'sync_type',
	];

	/**
	 * @return HasOne
	 */
	public function user(): HasOne
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
}
