<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\TranslationController;
use App\Http\Controllers\API\TagController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('languages', LanguageController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('translations', TranslationController::class);
    Route::get('translations/export/json', [TranslationController::class, 'export']);
    Route::get('translations/search', [TranslationController::class, 'search']);
});
