<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    public function show()
    {
        return response()->json([
            "message" => "profile picture retrieved successfully",
            "data" => request()->user()->getPictureUrl()
        ]);
    }

    public function store()
    {

        $user = request()->user();
        request()->validate([
            'picture' => 'required|file|mimes:png,jpg|max:4048'
        ]);
        if($user->picture) {
            $user->deletePicture();
        }
        $picture = request()->file('picture');

        $path = Storage::disk('public')->put( $user->id . '/', $picture);

        $user->setPicture($path);

        return response()->json([
            "message" => "profile picture saved successfully",
            "data" => $user->getPictureUrl()
        ]);

    }

    public function destroy()
    {
        request()->user()->deletePicture();
        return response()->json([
            "message" => "profile picture removed successfully"
        ]);
    }
}
