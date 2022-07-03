<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    /**
     * Password show test
     *
     * @return void
     */
    public function test_user_can_update_his_password()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $res = $this->postJson(route('password.update'), [
            "old_password" => "password",
            "password" => "passwordx",
            "password_confirmation" => "passwordx",
        ]);

        $res->assertOk();

        $res->assertJsonPath('message', 'password changes successfully');
    }


    /**
     * Password update incorrect password test
     *
     * @return void
     */
    public function test_user_cannot_update_his_password_if_current_password_is_incorrect()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $res = $this->postJson(route('password.update'), [
            "old_password" => "passwordx",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $res->assertUnprocessable();

        $res->assertJsonValidationErrorFor('old_password');
    }


    /**
     * Password forgot and reset test
     *
     * @return void
     */
    public function test_user_recieves_a_password_reset_email_and_can_reset_his_password()
    {
        Notification::fake();

        $user = User::factory()->create();

        $res = $this->postJson(route('password.forgot'), [
            "email" => $user->email
        ]);

        $res->assertOk();
        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return 1;
        });


        $res = $this->postJson(route('password.reset'), [
            "email" => $user->email,
            "token" => $token,
            "password" => "newpassword",
            "password_confirmation" => "newpassword"
        ]);

        $user = User::find($user->id);

        $res->assertOk();

        $this->assertTrue(Hash::check("newpassword", $user->password));

    }


        /**
     * Password reset validation test
     *
     * @return void
     */
    public function test_user_cannot_reset_his_password_when_data_is_invalid()
    {

        $user = User::factory()->create();

        $res = $this->postJson(route('password.reset'), [
            "email" => $user->email,
            "token" => "soemfaketokenobviously",
            "password" => "newpassword",
            "password_confirmation" => "newpassword"
        ]);


        $res->assertUnprocessable();



        $res = $this->postJson(route('password.reset'), [
            "email" => $user->email,
            "token" => "soemfaketokenobviously",
            "password" => "newpassword",
            "password_confirmation" => "newxxpassword"
        ]);


        $res->assertUnprocessable();

        $res->assertJsonValidationErrorFor('password');

    }
}
