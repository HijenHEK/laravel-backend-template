<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
