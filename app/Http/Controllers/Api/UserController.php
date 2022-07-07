<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'users retrieved successfully',
            "users" => UserResource::collection(User::paginate(20))->response()->getData(true)
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
            "name" => "required|string|min:5",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|string"
        ]);

        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);

        event(new Registered($user));

        return response()->json([
            'message' => 'user added successfully',
            "data" => UserResource::make($user)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            "message" => "user retrieved successfully",
            "data" => UserResource::make($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => "sometimes|string|min:5",
            "email" => "sometimes|email|unique:users,email," . $user->id,
            "password" => "sometimes|min:8|string"
        ]);

        if (isset($data["password"])) {
            $data["password"]  = Hash::make($data["password"]);
        }

        $user->update($data);

        return response()->json([
            'message' => 'user updated successfully',
            "data" => UserResource::make($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if($user->id == auth()->user()->id, Response::HTTP_UNAUTHORIZED, 'Ooops somthing went wrong! Maybe you are not allowed to perform this');
        $user->delete();
        return response()->json([
            "message" => "user deleted successfully"
        ]);
    }
}
