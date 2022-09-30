<?php

namespace App\Http\Controllers\Api\AdminAuthentication;

use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'email' => 'required'
        ]);



        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function reset(Request $request)
    {

            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($admin, $password) {
                    $admin->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $admin->save();

                    event(new PasswordReset($admin));
                }
            );

            return $status === Password::PASSWORD_RESET
                        ? response()->json(['status'=> __($status)])
                        : response()->json(['email' => [__($status)]]);
    }
}
