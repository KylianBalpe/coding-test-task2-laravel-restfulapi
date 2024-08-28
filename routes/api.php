<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
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

Route::post("/user/register", [UserController::class, "register"])->name("register");
Route::post("/user/login", [UserController::class, "login"])->name("login");

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('admin')->group(function () {
        Route::post("/category", [CategoryController::class, "create"]);
        Route::put("/category/{id}", [CategoryController::class, "update"]);
        Route::delete("/category/{id}", [CategoryController::class, "delete"]);
    });

    Route::get("/categories", [CategoryController::class, "getAll"]);
});
