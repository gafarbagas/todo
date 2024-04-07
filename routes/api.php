<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/', [TaskController::class, 'getAll'])->name('tasks');
        Route::post('/', [TaskController::class, 'create']);
        Route::put('/{id}/description', [TaskController::class, 'updateDescription']);
        Route::put('/sort', [TaskController::class, 'updatePriorities']);
        Route::put('/{id}/completed', [TaskController::class, 'updateCompleted']);
        Route::delete('/{id}', [TaskController::class, 'delete']);
    });
});
