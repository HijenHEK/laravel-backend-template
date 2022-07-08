<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProfilePictureControllerTest extends TestCase
{

    protected $user ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Profile_picture show test
     *
     * @return void
     */
    public function test_user_can_get_his_profile_picture()
    {

        $res = $this->getJson(route('profile.picture.show'));

        $res->assertOk();

        $res->assertJsonPath('data', $this->user->getPictureUrl());
    }


    /**
     * profile_picture update test
     *
     * @return void
     */
    public function test_user_can_update_his_profile_picture()
    {


        $response = $this->postJson(route("profile.picture.store"), [
            'picture' => UploadedFile::fake()->image('pic.png')
        ]);

        $response->assertOk();

    }

    /**
     * Delete profile picture test
     *
     * @return void
     */
    public function test_user_can_delete_his_profile_picture()
    {

        $response = $this->deleteJson(route("profile.picture.destroy"));

        $response->assertOk();

        $this->assertNull(User::find($this->user->id)->picture);
    }


}
