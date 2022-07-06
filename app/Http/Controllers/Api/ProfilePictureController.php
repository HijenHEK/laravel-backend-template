<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    public function show()
    {
        return response()->json([
            "message" => "profile picture retrieved successfully",
            "data" => request()->user()->getPicture()
        ]);

    }

    public function store()
    {
        $user = request()->user() ;
        request()->validate([
            'picture' => 'required|file|mimes:png,jpg|max:4048'
        ]);

        $picture = request()->file('picture');

        Storage::disk('public')->put($user->id . '/' .$picture->getClientOriginalName(), $picture);

        dd($user->id . '/' .$picture->getClientOriginalName());

        $user->setPicture();
    }

    public function destroy()
    {
        request()->user()->deletePicture();
        return response()->json([
            "message" => "profile picture removed successfully"
        ]);
    }
}
