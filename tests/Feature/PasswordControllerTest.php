<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
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

        $res->assertJsonPath('message' , 'password changes successfully');
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




}
