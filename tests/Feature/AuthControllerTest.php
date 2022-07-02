<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * Registration test
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $data = [
            "email" => "foulen@example.com",
            "name" => "foulen",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->postJson(route("register"), $data);

        $response->assertOk();

        $this->assertModelExists(User::where("email", $data["email"])->first());
        $this->assertDatabaseHas("users", [
            "email" => "foulen@example.com",
            "name" => "foulen"
        ]);
    }


    /**
     * Registration validation test
     *
     * @return void
     */
    public function test_user_cannot_register_when_input_isnt_valid()
    {
        User::factory()->create(["email" => "foulen@example.com"]);
        $data = [
            "email" => "foulen@example.com",
            "name" => "foulen",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->postJson(route("register"), $data);

        $response->assertJsonValidationErrorFor("email");


        $data = [
            "email" => "foulenx@yama.com",
            "name" => "foulen",
            "password" => "password",
            "password_confirmation" => "xpassword"
        ];

        $response = $this->postJson(route("register"), $data);

        $response->assertJsonValidationErrorFor("password");
    }

    /**
     * Login test
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $creadentials = [
            "email" =>  "foulen@example.com",
            "password" =>  "password",
        ];
        User::factory()->create(["email" => $creadentials["email"]]);

        $response = $this->postJson(route("login"), $creadentials);

        $response->assertOk();

        $response->assertJsonPath("message" , "user logged in successfully");

    }
}
