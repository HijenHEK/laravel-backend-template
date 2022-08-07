@component('mail::message')
# MFA code

#### use the code below to verify your MFA

## {{ $code }}

#### code expires in {{ config('mfa.expiration') }} minutes .

Thanks,<br>
{{ config('app.name') }}
@endcomponent
