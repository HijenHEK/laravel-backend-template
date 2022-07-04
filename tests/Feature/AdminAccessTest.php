<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    /**
     * admin middleware test
     *
     * @return void
     */
    public function test_user_cannot_access_admin_protected_route()
    {


        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson(route("admin.check"));

        $response->assertUnauthorized();

        $user->setRole(User::ROLE_ADMIN);

        $response = $this->getJson(route("admin.check"));

        $response->assertOk();

    }
}
