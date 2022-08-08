<?php

namespace App\Traits;

use App\Mail\MfaMail;
use Illuminate\Http\Request;
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
        $token = request()->user()->currentAccessToken();

        return !$token->mfa_code;
    }

    public function sendMfaCode($token)
    {

        if(!$token->mfa_code) return;

        Mail::to($this)->send(new MfaMail($token->mfa_code));

    }

}
