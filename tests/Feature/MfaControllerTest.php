<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class MfaControllerTest extends TestCase
{
    /**
     * update mfa test
     *
     * @return void
     */
    public function test_user_can_update_his_mfa_value()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->putJson(route("mfa.update"), [
            'active' => true
        ]);

        $response->assertOk();

        $response->assertJsonPath('message', 'MFA is now enabled' );


        $response = $this->putJson(route("mfa.update"), [
            'active' => false
        ]);

        $response->assertOk();

        $response->assertJsonPath('message', 'MFA has been disabled successfully' );
    }


        /**
     * verify mfa test
     *
     * @return void
     */
    public function test_user_can_verify_his_mfa_value()
    {
        $code = 845424;

        $user = User::factory()->create();

        $user->mfa  = true;
        $user->save();

        $token = $user->createToken('blayme');

        $plainText = $token->plainTextToken;

        $token = PersonalAccessToken::findToken($plainText);

        $token->mfa_code = $code ;
        $token->mfa_expires_at = now()->addMinutes(9)->format('Y-m-d h:i:s') ;
        $token->save();

        $response = $this->postJson(route("mfa.verify"), [
            'code' => $code
        ],[
            'Authorization' => 'Bearer ' . $plainText
        ]);

        $response->assertOk();

        $this->getJson(route('mfa.check'))->assertOk();
    }


}
