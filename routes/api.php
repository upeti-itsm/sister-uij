<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sahabat/callback-va', [\App\Http\Controllers\MahasiswaPage\Akademik\TanggunganController::class, 'callback_va']);
Route::post('sahabat/callback-payment', [\App\Http\Controllers\MahasiswaPage\Akademik\TanggunganController::class, 'callback_payment']);
