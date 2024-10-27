<?php

namespace App\Features\Users\Domains\Models;

use App\Features\Users\Domains\Constants\UserConstants;
use App\Features\Users\Domains\Query\UserScopes;
use App\Features\Users\Http\v1\Data\UserData;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $identifier
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $email_verified_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends Authenticatable implements JWTSubject, UserConstants
{

    use Notifiable, UserScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array<mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }


    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->identifier = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
    }

    /**
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $name = $this->name;
        return "https://ui-avatars.com/api/?size=40&rounded=true&name={$name}";
    }

    /**
     * @param UserData $userData
     *
     * @return self
     */
    public static function persistUser(UserData $userData): self
    {
        return self::create($userData->only('name', 'email', 'password')->toArray());
    }


    /**
     * @param UserData $userData
     *
     * @return bool
     */
    public function updateUser(UserData $userData): bool
    {
        return  $this->update([
            'name' => $userData->name,
            'email' => $userData->email,
        ]);
    }
}
