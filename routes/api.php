<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BantuanController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware([JWTMiddleware::class])->group(function () {

        Route::get('bantuan', [BantuanController::class, 'index']);
        Route::post('bantuan/update/status', [BantuanController::class, 'storeStatus']);
        Route::post('bantuan/store', [BantuanController::class, 'store']);
        Route::get('bantuan/statistik', [BantuanController::class, 'statistik_laporan']);

        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    // Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});
