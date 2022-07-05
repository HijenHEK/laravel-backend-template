<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);
    }
    /**
     * list users test
     *
     * @return void
     */
    public function test_admin_can_list_users()
    {
        $res = $this->getJson(route('users.index'));
        $res->assertOk();
    }

    /**
     * Admin one user test
     *
     * @return void
     */
    public function test_admin_can_get_a_user()
    {
        $res = $this->getJson(route('users.show', 1));
        $res->assertOk();
    }


    /**
     * Admin can create a user test
     *
     * @return void
     */
    public function test_admin_can_create_a_user()
    {
        $res = $this->postJson(route('users.store'), [
            'name' => 'foulen',
            'email' => 'foulenx@example.co',
            'password' => 'password'
        ]);
        $res->assertOk();
        $this->assertModelExists(User::where('email' , 'foulenx@example.co')->first());
    }

        /**
     * Admin can update a user test
     *
     * @return void
     */
    public function test_admin_can_update_a_user()
    {
        $user = User::factory()->create([
            'name' => 'foulen',
            'email' => 'foulen@example.co'
        ]);
        $res = $this->putJson(route('users.update', $user->id),  [
            'name' => 'foulenx',
            'email' => 'foulenx@example.co',
            'password' => 'password'
        ]);
        $res->assertOk();
        $this->assertEquals(User::find($user->id)->email , 'foulenx@example.co');
    }


        /**
     * Admin can delete one user test
     *
     * @return void
     */
    public function test_admin_can_delete_a_user()
    {
        $user = User::factory()->create();
        $res = $this->deleteJson(route('users.destroy', $user->id));
        $res->assertOk();
    }
}
