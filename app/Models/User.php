<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    protected $fillable = ['external_id', 'email', 'password', 'first_name', 'last_name', 'picture', 'type'];

    protected $hidden = ['password'];

    protected $casts = ['type' => UserType::class];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
