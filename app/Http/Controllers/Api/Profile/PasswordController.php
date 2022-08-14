<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\str;

class PasswordController extends Controller
{

    /**
     * Update current password
     *
     * @param  Request  $request
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            "old_password" => "required|current_password:sanctum",
            "password" => "required|confirmed"
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password'])
        ]);

        return response()->json([
            'message' => 'password changes successfully'
        ]);
    }

    /**
     * Send reset password email
     *
     * @param  Request  $request
     */
    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::broker()->sendResetLink(
            $request->only('email')
        );

        return response()->json([
            "message" => "If you've provided a valid registered e-mail adress, you should get a password recovery e-mail shortly.",
        ]);
    }


    /**
     * reset the user password
     *
     * @param  Request  $request
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
        ? response()->json([
            "message" => "password has been updated successfully !",
        ])
        : response()->json([
            "message" => "Ooops ssomthing went wrong !",
            "errors" => [__($status)],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);


    }



}
