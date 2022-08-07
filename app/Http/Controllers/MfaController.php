<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MfaController extends Controller
{
    public function __invoke(Request $request)
    {

        $data = $request->validate([
            'active'=> 'required|boolean'
        ]);

        $user = request()->user();

        $user->mfa  = $data['active'];
        $user->save();

        return response()->json([
            'message' => $user->mfa ? 'MFA is now enabled' : 'MFA has been disabled successfully'
        ]);
    }
}
