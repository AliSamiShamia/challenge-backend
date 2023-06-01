<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
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

Route::get('home', [ArticleController::class, 'index']);

Route::get('profile', [UserController::class, 'profile']);
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::get('sources', [SourceController::class, 'index']);


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('preference', [UserController::class, 'preference']);
    Route::get('preference', [UserController::class, 'myPreference']);
});


