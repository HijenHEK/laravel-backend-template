<?php

namespace App\Models;

use App\Traits\HasMfa;
use App\Traits\HasPicture;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, HasPicture, HasMfa;


    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

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
    ];



    public function setRole(int $role = self::ROLE_USER)
    {
        if (auth()->user() == $this && $this->role == self::ROLE_ADMIN) {
            return;
        }
        $this->role = $role;
        $this->save();
        return $this;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * User owned attachment
     */
    public function uploads()
    {
        return $this->hasMany(Attachment::class , 'owner_id');
    }


        /**
     * Create a new personal access token for the user.
     * -- Overrides HasApiToken method
     * @param  string  $name
     * @param  array  $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
        ]);


        if ($this->isMfaActive()) {
            $token->mfa_code = (string) rand(100000, 999999);
            $token->mfa_expires_at = now()->addMinutes(config('mfa.expiration'))
                ->format('Y-m-d h:i:s');
            $token->save();

            $this->sendMfaCode($token);
        }

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }
}
