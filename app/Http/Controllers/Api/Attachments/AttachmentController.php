<?php

namespace App\Http\Controllers\Api\Attachments;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'attachments retrived successfully',
            'attachments' => auth()->user()->uploads
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'attachment' => 'required|file|max:4096'
        ]);

        $file = $request->file('attachment');
        $name = $file->getClientOriginalName();
        $path = now()->format('d/m/y');

        $path = Storage::disk('local')->putFile($path, $file);

        $attachment = auth()->user()->uploads()->create([
            'name' => $name,
            'path' => $path
        ]);

        return response()->json([
            'message' => 'attachment uploaded successfully',
            'attachment' => $attachment
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attachment = Attachment::find($id);
        abort_unless($attachment && $attachment->owner_id == auth()->id(), Response::HTTP_BAD_REQUEST , 'Missing resource or unauthorized action');

        return response()->json([
            'message' => 'attachment retrived successfully',
            'file' => $attachment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attachment = Attachment::find($id);
        abort_unless($attachment && $attachment->owner_id == auth()->id(), Response::HTTP_BAD_REQUEST , 'Missing resource or unauthorized action');

        Storage::delete($attachment->path);

        $attachment->delete();

        return response()->json([
            'message' => 'attachment deleted successfully'
        ]);
    }
}
