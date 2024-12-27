<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CastMovieController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// /api/v1/

Route::prefix('v1')->group(function () {
    //Casts
    Route::apiResource('cast', CastController::class);

    //Genres
    Route::apiResource('genre', GenreController::class);

    //Movie
    Route::apiResource('movie', MovieController::class);

    //Cast Movie
    Route::apiResource('cast-movie', CastMovieController::class);

    //Roles
    Route::apiResource('role', RoleController::class)->middleware(['auth:api', 'admin']);

    //Auth
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/me', [AuthController::class, 'currentuser'])->middleware('auth:api');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/verification-email', [AuthController::class, 'verifikasi'])->middleware('auth:api');
        Route::post('/generate-otp-code', [AuthController::class, 'generateOtp'])->middleware('auth:api');
    });

    //Profile
    Route::post('/profile', [ProfileController::class, 'storeupdate'])->middleware(['auth:api', 'verifiedAccount']);

    //Reviews
    Route::post('/review', [ReviewController::class, 'storeupdate'])->middleware(['auth:api', 'verifiedAccount']);
});
