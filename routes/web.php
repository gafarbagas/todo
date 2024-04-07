<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/tasks', [TaskController::class, 'getAll'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'create']);
    Route::put('/tasks/{id}/description', [TaskController::class, 'updateDescription']);
    Route::put('/tasks/sort', [TaskController::class, 'updatePriorities']);
    Route::put('/tasks/{id}/completed', [TaskController::class, 'updateCompleted']);
    Route::delete('/tasks/{id}', [TaskController::class, 'delete']);
});
