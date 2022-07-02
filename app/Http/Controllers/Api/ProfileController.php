<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            "password" => "required|current_password:sanctum",
            "name" => "required|string|min:5",
            "email" => "required|email|unique:users,email"
        ]);
        unset($data["password"]);

        $user = $request->user();
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
}
