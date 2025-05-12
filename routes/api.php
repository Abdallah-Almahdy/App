<?php

use App\Http\Controllers\AdminCommitteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BanerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionOrderController;
use App\Http\Controllers\commiteeJionController;
use App\Http\Controllers\committee_sessionsController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventsImagesController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\oAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\userProfileController;
use App\Http\Resources\AwardResource;
use App\Models\Admin;
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



Route::middleware('auth:sanctum')->get('/profile', [userProfileController::class, 'profile']);
Route::middleware('auth:sanctum')->post('/profile', [userProfileController::class, 'updateProfile']);




//admin routes
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);


Route::middleware(['admin', 'auth:sanctum'])->group(function () {
    Route::apiResource('/events', EventController::class)->except(['index', 'show']);

    Route::apiResource('/blogs', BlogController::class)->except(['index', 'show','update']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);

    Route::apiResource('/awards', AwardController::class)->except(['index', 'show']);

    Route::apiResource('/materials', MaterialController::class)->except(['index', 'show']);

    Route::post('/inactive-members', [commiteeJionController::class, 'inactiveMembers']);
    Route::post('/approveMemberRequest', [commiteeJionController::class, 'approveMemberRequest']);
    Route::post('/rejectMemberRequest', [commiteeJionController::class, 'rejectMemberRequest']);
});




Route::middleware([ 'auth:sanctum'])->group(function () {

    Route::apiResource('/events', EventController::class)->only(['index', 'show']);

    Route::apiResource('/blogs', BlogController::class)->only(['index', 'show']);

    Route::apiResource('/awards', AwardController::class)->only(['index', 'show']);

    Route::apiResource('/materials', MaterialController::class)->only(['index', 'show']);

    Route::POST('/EventImage/{id}', [EventsImagesController::class, 'update']);
    Route::delete('/EventImage/{id}', [EventsImagesController::class, 'destroy']);

    Route::apiResource('/committees/{commitee_id}/tasks', TaskController::class)->except(['index', 'show']);
    Route::apiResource('/committees/{commitee_id}/sessions', committee_sessionsController::class)->except(['index', 'show']);

    Route::apiResource('/tasks', TaskController::class)->only(['index', 'show']);
    Route::apiResource('/sessions', committee_sessionsController::class)->only(['index', 'show']);


    Route::post('/reqest-join', [commiteeJionController::class, 'memberRequests']);


    Route::apiResource('/committees', CommitteeController::class)->only(['index', 'show']);
    route::middleware('auth:sanctum')->get('/committees/{committee_id}/requests', [AdminCommitteController::class, 'members']);

    Route::apiResource('/baners', BanerController::class)->except('update');
    Route::post('/baners/{id}', [BanerController::class, 'update']);

    Route::apiResource('/collections', CollectionController::class)->except('update');
    Route::post('/collections/{id}', [CollectionController::class, 'update']);

    Route::apiResource('/products', ProductController::class)->except('update');
    Route::post('/products/{id}', [ProductController::class, 'update']);

    Route::apiResource('/collections-orders', CollectionOrderController::class)->only(['index', 'store', 'delete']);
    Route::apiResource('/products-orders', ProductOrderController::class)->only(['index', 'store', 'delete']);
});




//super admin routes
Route::middleware('superAdmin')->group(function () {
    Route::apiResource('/committees', CommitteeController::class)->except(['index', 'show']);
    Route::post('/committees/setAdmin', [AdminCommitteController::class, 'setAdmin']);
    Route::post('/committees/removeAdmin', [AdminCommitteController::class . 'removeAdmin']);
    Route::post('/admin/register', [AdminController::class, 'register']);

    Route::middleware('auth:sanctum')->get('/admins', function (Request $request) {

        $admins = Admin::all();
        return response()->json([
            'admins' => $admins
        ]);
    });
});




Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout']);

Route::get('auth/google/callback', [oAuthController::class, 'handleGoogleCallback']);
