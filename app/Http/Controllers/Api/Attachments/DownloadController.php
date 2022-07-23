<?php

namespace App\Http\Controllers\Api\Attachments;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download a file
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Attachment $attachment)
    {

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
