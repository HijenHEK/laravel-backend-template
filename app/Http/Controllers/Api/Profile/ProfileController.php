<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class ProfileController extends Controller
{

    /**
     * Show the profile data
     *
     * @param  Request  $request
     */

    public function show(Request $request)
    {
        return response()->json([
            "message" => "profile data retrieved successfully",
            "data" => $request->user()
        ]);
    }
    /**
     * update the current profile data
     *
     * @param  Request  $request
     */

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            "password" => "required|current_password:sanctum",
            "name" => "required|string|min:5",
            "email" => "required|email|unique:users,email,".$user->id
        ]);
        unset($data["password"]);

        $user->update($data);

        return response()->json([
            "message" => "profile updated successfully",
            "data" => $user
        ]);
    }
    /**
     * destroy the current profile
     *
     * @param  Request  $request
     */

    public function destroy(Request $request)
    {

        $request->user()->delete();
        return response()->json([
            "message" => "profile deleted successfully"
        ]);
    }



    /**
     * Verify the current profile email address
     *
     * @param  EmailVerificationRequest  $request
     */

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json([
            'message' => 'email has been verified successfully'
        ]);
    }


}
