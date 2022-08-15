<?php

use App\Http\Controllers\Api\Attachments\AttachmentController;
use App\Http\Controllers\Api\Attachments\DownloadController;
use App\Http\Controllers\Api\Authentication\AuthController;
use App\Http\Controllers\Api\Profile\PasswordController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\ProfilePictureController;
use App\Http\Controllers\Api\Profile\UserController;
use App\Http\Controllers\Api\Authentication\MfaController;
use App\Http\Controllers\Api\Authentication\PasswordResetController;
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

Route::middleware('throttle:5,1')->post('/verify', [AuthController::class, 'verify'])->name('verify');

Route::middleware('guest')->group(function () {
    Route::post('/forgot-password', [PasswordResetController::class , 'send'])->name('password.email');
    Route::post('/reset-password', [PasswordResetController::class , 'reset'])->name('password.reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/token', [AuthController::class, 'token'])->name('token');
        Route::post('/verify', [AuthController::class, 'verify'])->name('verify');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/verify/{id}/{hash}', [ProfileController::class, 'verify'])->name('verification.verify');

        Route::get('/picture', [ProfilePictureController::class, 'show'])->name('profile.picture.show');
        Route::post('/picture', [ProfilePictureController::class, 'store'])->name('profile.picture.store');
        Route::delete('/picture', [ProfilePictureController::class, 'destroy'])->name('profile.picture.destroy');
    });


    Route::prefix('password')->group(function () {
        Route::post('/update', [PasswordController::class, 'update'])->name('password.update');
    });

    Route::prefix('attachments')->group(function () {
        Route::get('/', [AttachmentController::class, 'index'])->name('attachments.index');
        Route::post('/', [AttachmentController::class, 'store'])->name('attachments.store');
        Route::get('/download', [DownloadController::class, 'all'])->name('attachments.download.all');
        Route::get('/{id}/download', [DownloadController::class, 'one'])->name('attachments.download.one');
        Route::get('/{id}', [AttachmentController::class, 'show'])->name('attachments.show');
        Route::delete('/{id}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    });

    // email verified middleware
    Route::middleware('verified')->group(function () {

        // returns verified if user can acceess it
        Route::get('/verified', function () {
            return response()->json([
                'message' => 'verified'
            ]);
        })->name('verified.check');
    });

    // mfa verified middleware
    Route::middleware('mfa')->group(function () {

        // returns verified if user can acceess it
        Route::get('/mfa-check', function () {
            return response()->json([
                'message' => 'mfa verified'
            ]);
        })->name('mfa.check');
    });
    Route::put('/mfa' , [MfaController::class ,'update'])->name('mfa.update');
    Route::post('/mfa' , [MfaController::class ,'verify'])->name('mfa.verify');
    // admin middleware group
    Route::middleware('admin')->group(function () {

        // returns verified if user can acceess it
        Route::get('/admin', function () {
            return response()->json([
                'message' => 'admin'
            ]);
        })->name('admin.check');

        Route::apiResource('/users', UserController::class);
    });
});

Route::middleware('guest:sanctum')->group(function () {
    Route::prefix('password')->group(function () {
        Route::post('/forgot', [PasswordController::class, 'forgot'])->name('password.forgot');
        Route::post('/reset', [PasswordController::class, 'reset'])->name('password.reset');
    });
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
