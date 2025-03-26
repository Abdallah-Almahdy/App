<?php

use App\Http\Controllers\AdminCommitteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\committee_sessionsController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventsImagesController;
use App\Http\Controllers\oAuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Resources\AwardResource;
use App\Models\User;
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

Route::middleware('auth:sanctum')->get('/admin', function (Request $request) {
    return $request->user();
});


//admin routes
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);



Route::apiResource("/events", EventController::class);
Route::apiResource("/blogs", BlogController::class);
Route::apiResource('/awards', AwardController::class);


Route::POST('/EventImage/{id}', [EventsImagesController::class, 'update']);
Route::delete('/EventImage/{id}', [EventsImagesController::class, 'destroy']);


Route::apiResource('/committees', CommitteeController::class);
Route::middleware('auth:sanctum')->apiResource('/committees/{commitee_id}/tasks', TaskController::class);
Route::middleware('auth:sanctum')->apiResource('/committees/{commitee_id}/sessions', committee_sessionsController::class);





//super admin routes
Route::middleware('superAdmin')->group(function () {

    Route::post('/admin/register', [AdminController::class, 'register']);
    Route::post('/committees/setAdmin', [AdminCommitteController::class, 'setAdmin']);
    Route::post('/committees/removeAdmin', [AdminCommitteController::class . 'removeAdmin']);
});



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/logout', [UserController::class, 'logout']);

Route::get('auth/google/callback', [oAuthController::class, 'handleGoogleCallback']);
