<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Authenticate a user
     *
     * @param  Request  $request
     */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = auth()->user()->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'user logged in successfully',
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
     * Register a new user
     *
     * @param  Request  $request
     */

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|min:5",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|string|confirmed"
        ]);

        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);

        event(new Registered($user));


        return response()->json([
            'message' => 'user registered successfully, please check yout inbox and verify your email address !'
        ]);
    }

    /**
     * Sign out a new user
     *
     * @param  Request  $request
     */

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'user logged out successfully'
        ]);
    }
}
