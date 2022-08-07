<?php

namespace App\Traits;



trait HasMfa
{
    protected $mfa_code ;

    public function isMfaActive(): bool
    {
        return $this->mfa;
    }

    public function sendMfaCode()
    {
        if(!$this->mfa_code) return;

    }

}
