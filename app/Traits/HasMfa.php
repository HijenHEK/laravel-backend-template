<?php

namespace App\Traits;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

trait HasMfa
{
    public function isMfaActive() : bool
    {
        return $this->mfa;
    }

    public function sendMfaCode($token)
    {
        $code = PersonalAccessToken::findToken($token)->mfa_code;

    }

}
