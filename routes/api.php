<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventsImagesController;
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

Route::apiResource("/events", EventController::class);
Route::apiResource("/blogs",BlogController::class);


Route::POST('/EventImage/{id}',[EventsImagesController::class, 'update']);
Route::delete('/EventImage/{id}', [EventsImagesController::class, 'destroy']);
