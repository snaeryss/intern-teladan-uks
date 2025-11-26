<?php

namespace App\Models;

use App\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

/**
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static admin() return non student accounts
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'secret',
        'type',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'secret',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Scope a query to exclude users with type AccountType::Student.
     */
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('type', '!=', AccountTypeEnum::Student);
    }

    /**
     * @return HasOne
     */
    public function studentAccount(): HasOne
    {
        return $this->hasOne(StudentAccount::class, 'user_id', 'id');
    }
}
