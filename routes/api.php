<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout' , [AuthController::class, 'logout'])->name('logout');

    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class , 'show'])->name('profile.show');
        Route::post('/update', [ProfileController::class , 'update'])->name('profile.update');
        Route::post('/delete', [ProfileController::class , 'destroy'])->name('profile.destroy');
    });
    Route::prefix('password')->group(function(){
        Route::post('/update', [PasswordController::class , 'update'])->name('password.update');
    });

});

Route::post('/login' , [AuthController::class, 'login'])->name('login');
Route::post('/register' , [AuthController::class, 'register'])->name('register');
