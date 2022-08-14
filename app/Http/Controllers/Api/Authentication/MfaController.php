<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class MfaController extends Controller
{
    public function update(Request $request)
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

    /**
     *  user authenticate by verifying the mfa code here
     */
    public function verify(Request $request)
    {

        abort_unless($request->user()->isMfaActive() , Response::HTTP_UNAUTHORIZED);

        $data = $request->validate([
            'code'=> 'required'
        ]);

        $token = $request->user()->currentAccessToken();

        abort_unless($token->mfa_code , Response::HTTP_UNAUTHORIZED);

        abort_if($token->mfa_code != $data['code'] || now()->lt($token->mfa_expires_at), Response::HTTP_BAD_REQUEST , 'Invalid Code');

        $token->mfa_code = null;
        $token->mfa_expires_at = null;
        $token->save();


        return response()->json([
            'message' => 'MFA has been verified successfully'
        ]);
    }
}
