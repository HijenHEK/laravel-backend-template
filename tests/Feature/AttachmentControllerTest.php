<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentControllerTest extends TestCase
{

    /**
     * @var User
     */
    protected $user_1;

    /**
     * @var User
     */
    protected $user_2;

    /**
     * @var Attachment
     */
    protected $attachment_1;

    /**
     * @var Attachment
     */
    protected $attachment_2;


    public function setUp(): void
    {
        parent::setUp();
        $this->user_1 = User::factory()->create();
        $this->user_2 = User::factory()->create();

        $this->attachment_1 = $this->user_1->uploads()->create([
            'name' => 'file',
            'path' => 'path',
        ]);

        $this->attachment_2 = $this->user_2->uploads()->create([
            'name' => 'file 2',
            'path' => 'path 3',
        ]);
    }


    /**
     * Get all logged in user uploads test
     *
     * @return void
     */
    public function test_get_all_logged_in_user_uploads()
    {
        $this->actingAs($this->user_1);

        $response = $this->getJson(route("attachments.index"));

        $response->assertOk();

        $response->assertJsonCount(1, 'attachments');
    }

    /**
     * Get a single attachment test
     *
     * @return void
     */
    public function test_a_user_can_get_a_single_attachment()
    {
        $this->actingAs($this->user_1);

        $response = $this->getJson(route("attachments.show", $this->attachment_1->id));

        $response->assertOk();

        $response->assertJsonPath('file.name', $this->attachment_1->name);
    }


    /**
     * Get a store and delete single attachment test
     *
     * @return void
     */
    public function test_a_user_can_store_and_delete_a_single_attachment()
    {
        $file = UploadedFile::fake()->create('uploaded_file');


        $this->actingAs($this->user_1);

        $response = $this->postJson(route("attachments.store"), [
            "attachment" => $file
        ]);

        $response->assertOk();

        $response->assertJsonPath('attachment.name', 'uploaded_file');

        $attachment = Attachment::find($response->json('attachment')['id']);

        Storage::disk('local')->assertExists($attachment->path);


        // deleting

        $response = $this->deleteJson(route("attachments.destroy", $attachment->id));

        $response->assertOk();

        Storage::disk('local')->assertMissing($attachment->path);

        $this->assertModelMissing($attachment);
    }


    /**
     * user cannot get an attachment he dosnt own
     *
     * @return void
     */
    public function test_a_user_cannot_get_an_attachment_he_dosnt_own()
    {
        $this->actingAs($this->user_1);

        $response = $this->getJson(route("attachments.show", $this->attachment_2->id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * user cannot destroy an attachment he dosnt own
     *
     * @return void
     */
    public function test_a_user_cannot_destroy_an_attachment_he_dosnt_own()
    {
        $this->actingAs($this->user_1);

        $response = $this->deleteJson(route("attachments.show", $this->attachment_2->id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }



    /**
     * user can download an attachment
     *
     * @return void
     */
    public function test_a_user_can_download_an_attachment()
    {
        $this->actingAs($this->user_1);

        $file = UploadedFile::fake()->image('image.png');


        $this->actingAs($this->user_1);

        $response = $this->postJson(route("attachments.store"), [
            "attachment" => $file
        ]);

        $attachment = Attachment::find($response->json('attachment')['id']);

        $response = $this->getJson(route("attachments.download.one", $attachment->id) . '?base64');

        $response->assertOk();

    }
}
