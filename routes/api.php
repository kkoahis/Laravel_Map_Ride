<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [App\Http\Controllers\LoginController::class, 'submit']);
Route::post('/login/verify', [App\Http\Controllers\LoginController::class, 'verify']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/driver', [App\Http\Controllers\DriverController::class, 'show']);
    Route::post('/driver', [App\Http\Controllers\DriverController::class, 'update']);

    Route::get('/trip/{trip}', [App\Http\Controllers\TripController::class, 'show']);
    Route::post('/trip', [App\Http\Controllers\TripController::class, 'store']);
    Route::post('trip/{trip}/accept', [App\Http\Controllers\TripController::class, 'accept']);
    Route::post('trip/{trip}/start', [App\Http\Controllers\TripController::class, 'start']);
    Route::post('trip/{trip}/end', 
    [App\Http\Controllers\TripController::class, 'end']);
    Route::post('trip/{trip}/location', [App\Http\Controllers\TripController::class, 'location']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});