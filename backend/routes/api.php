<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;

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

//Authentication
Route::post("auth/register", [AuthController::class, "register"]);
Route::post("auth/login", [AuthController::class, "login"]);
Route::post("auth/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");
Route::get("auth/profile", [AuthController::class, "profile"])->middleware("auth:sanctum");

//Categories
//Guests or users
Route::get("category/{category_name}", [CategoryController::class, "index"]);
Route::get("category", [CategoryController::class, "all_categories"]);
//Users
Route::get("user/category", [CategoryController::class, "user_categories"])->middleware("auth:sanctum");
Route::post("category", [CategoryController::class, "store"])->middleware("auth:sanctum");
Route::delete("category/{category_name}", [CategoryController::class, "destroy"])->middleware("auth:sanctum");

//Photo
//Guests or users
Route::get("search/{search_text}", [PhotoController::class, "search"]);
Route::get("photos", [PhotoController::class, "index"]);
Route::get("photos/{slug}", [PhotoController::class, "detail"]);
Route::get("photos/{slug}/download", [PhotoController::class, "download"]);
//Users
Route::get("user/photos", [PhotoController::class, "user_photos"])->middleware("auth:sanctum");
Route::get("user/photos/{slug}", [PhotoController::class, "user_photos_detail"])->middleware("auth:sanctum");
Route::post("user/photos", [PhotoController::class, "store"])->middleware("auth:sanctum");
Route::post("user/photos/{id}", [PhotoController::class, "update"])->middleware("auth:sanctum");
Route::delete("user/photos/{id}", [PhotoController::class, "destroy"])->middleware("auth:sanctum");

//Comments
Route::get("photos/{slug}/comments", [CommentController::class, "index"]);
Route::post("photos/{slug}/comments", [CommentController::class, "store"])->middleware("auth:sanctum");
Route::get("photos/{slug}/comments/{comment_id}", [CommentController::class, "detail"])->middleware("auth:sanctum");
Route::post("photos/{slug}/comments/{comment_id}", [CommentController::class, "update"])->middleware("auth:sanctum");
Route::delete("photos/{slug}/comments/{comment_id}", [CommentController::class, "destroy"])->middleware("auth:sanctum");

//Likes
Route::get("photos/{slug}/like", [LikeController::class, "index"])->middleware("auth:sanctum");
Route::post("photos/{slug}/like", [LikeController::class, "store"])->middleware("auth:sanctum");
Route::delete("photos/{slug}/like", [LikeController::class, "destroy"])->middleware("auth:sanctum");