<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', [RegisterController::class, "show"])->name('home');
Route::get('/register', [RegisterController::class, "register"]);
Route::post('/register', [RegisterController::class, "create"]);
Route::get("/read", [RegisterController::class, "read"])->name('list')->middleware('auth');

Route::get("/edit/{id}", [RegisterController::class, 'updateView']);
Route::post("/update/{id}", [RegisterController::class, "update"]);
Route::get("/delete/{id}", [RegisterController::class, 'delete']);

// ***************** Login *********************
Route::get("/login", [loginController::class, 'loginPage'])->name('login.page');
Route::post('/login', [loginController::class, 'login']);
Route::get("/logout", [loginController::class, 'logout'])->name("logout");
