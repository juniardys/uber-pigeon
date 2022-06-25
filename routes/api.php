<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\PigeonController;
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

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);

    // Pigeon
    Route::prefix('pigeon')->group(function () {
        Route::get('/', [PigeonController::class, 'get']);
        Route::post('/', [PigeonController::class, 'create']);
        Route::put('/{id}', [PigeonController::class, 'update']);
        Route::post('/{id}/toggle-status', [PigeonController::class, 'toggleStatus']);
        Route::delete('/{id}', [PigeonController::class, 'delete']);
    });
});
