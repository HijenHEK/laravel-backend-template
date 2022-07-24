<?php

namespace App\Http\Controllers\Api\Attachments;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

use function PHPUnit\Framework\fileExists;

class DownloadController extends Controller
{

    /**
     * Download all current user files
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {


        if(!auth()->user()->uploads()->count()) {
            return response()->json([
                'message' => 'current user has no uploads yet'
            ], 200);
        }

        $zip = new ZipArchive;

        $zip_path = storage_path('app/uploads_user_' . auth()->id() . '.zip') ;



        if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {




            foreach (auth()->user()->uploads as $key => $attachment) {
                if(fileExists(storage_path('app/' . $attachment->path))) {

                    $zip->addFile(storage_path('app/' . $attachment->path), $attachment->name);
                }
            }



            $zip->close();
        }

        if(! fileExists($zip_path)) {
            return response()->json([
                'message' => 'could not generate zip , retry later !'
            ], 500);
        }

        return response()->download(
            $zip_path,
            now()->format('d_m_y') . '_uploads.zip'
        )->deleteFileAfterSend();
    }



    /**
     * Download a file
     *
     * @return \Illuminate\Http\Response
     */
    public function one($id)
    {
        $attachment = Attachment::find($id);
        abort_unless($attachment && $attachment->owner_id == auth()->id(), Response::HTTP_BAD_REQUEST , 'Missing resource or unauthorized action');
        if (request()->has('base64')) {

            $content = Storage::disk('local')->get($attachment->path);
            return response()->json([
                'message' => 'attachments content retrived successfully',
                'data' => [
                    'name' => $attachment->name,
                    'content' =>  base64_encode($content)
                ]
            ]);
        }

        return response()->download(
            storage_path('app/' . $attachment->path),
            $attachment->name
        );
    }
}
