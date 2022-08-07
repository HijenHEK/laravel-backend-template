<?php

namespace App\Traits;

use App\Mail\MfaMail;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;

trait HasMfa
{
    protected $mfa_code ;

    public function isMfaActive(): bool | null
    {
        return $this->mfa;
    }

    public function isMfaVerified(): bool | null
    {
        $token = PersonalAccessToken::findToken(
            explode(' ', request()->header('authorization'))[1]
        );

        return !$token->mfa_code;
    }

    public function sendMfaCode($token = null)
    {

        $token = $token ?? PersonalAccessToken::findToken(
            explode(' ', request()->header('authorization'))[1]
        );

        if(!$token->mfa_code) return;

        Mail::to($this)->send(new MfaMail($token->mfa_code));

    }

}
