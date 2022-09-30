<?php

namespace App\Http\Controllers\Api\AdminAuthentication;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Authenticate a admin
     *
     * @param  Request  $request
     */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        abort_unless(
            $admin &&
                Hash::check(
                    $credentials['password'],
                    $admin->password
                ),
            Response::HTTP_UNAUTHORIZED,
            'The provided credentials are incorrect.'
        );


        $token = $admin->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'admin logged in successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    public function token(Request $request)
    {

        $token = auth()->user()->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Token generated successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    public function verify(Request $request)
    {
        $expiration = config('sanctum.expiration');

        $token = $request->user()->currentAccessToken();

        $valid = !$expiration || $token->created_at->gt(now()->subMinutes($expiration));

        return response()->json([
            'message' => $valid ? 'Given token is valid' : 'Given token is invalid'
        ], $valid ? 200 : 400);
    }

    /**
     * Register a new admin
     *
     * @param  Request  $request
     */

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|min:5",
            "email" => "required|email|unique:admins,email",
            "password" => "required|min:8|string|confirmed"
        ]);

        $data["password"] = Hash::make($data["password"]);

        $admin = Admin::create($data);

        event(new Registered($admin));


        return response()->json([
            'message' => 'admin registered successfully, please check yout inbox and verify your email address !'
        ]);
    }

    /**
     * Sign out a new admin
     *
     * @param  Request  $request
     */

    public function logout(Request $request)
    {
        $admin = $request->user();

        $admin->currentAccessToken()->delete();

        return response()->json([
            'message' => 'admin logged out successfully'
        ]);
    }
}
