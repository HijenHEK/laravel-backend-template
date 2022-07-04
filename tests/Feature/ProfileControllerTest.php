<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /**
     * Profile show test
     *
     * @return void
     */
    public function test_user_can_get_his_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $res = $this->getJson(route('profile.show'));

        $res->assertOk();

        $res->assertJsonPath('data', $user->toArray());
    }


    /**
     * profile update test
     *
     * @return void
     */
    public function test_user_can_update_his_profile_data()
    {
        $user = User::factory()->create(["email" => "foulen@example.com"]);

        $this->actingAs($user);

        $data = [
            "email" => "foulenx@example.com",
            "name" => "foulen",
            "password" => "password",
        ];

        $response = $this->postJson(route("profile.update"), $data);

        $response->assertOk();

        $response->assertJsonPath('data.email', $data['email']);
        $response->assertJsonPath('data.name', $data['name']);
    }

    /**
     * profile update incorrect password test
     *
     * @return void
     */
    public function test_user_cannot_update_his_profile_data_if_password_isnt_valid()
    {
        $user = User::factory()->create(["email" => "foulen@example.com"]);

        $this->actingAs($user);

        $data = [
            "email" => "foulenx@example.com",
            "name" => "foulen",
            "password" => "wrongpasswordhere!",
        ];

        $response = $this->postJson(route("profile.update"), $data);

        $response->assertUnprocessable();

        $response->assertJsonValidationErrorFor("password");
    }

    /**
     * Delete profile test
     *
     * @return void
     */
    public function test_user_can_delete_his_profile()
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route("profile.destroy"));

        $response->assertOk();

        $this->assertNull(User::find($user->id));
    }


}
