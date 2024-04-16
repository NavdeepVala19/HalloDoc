<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LoginController;
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

Route::get('/', [Controller::class,'home']);


Route::get('/index  ',[FormController::class,'index'])->name('index');

Route::get('/create',[FormController::class,'create']);
Route::post('/create-data',[FormController::class,'store']);

Route::get('/read',[FormController::class,'read'])->name('read')->middleware('auth');
Route::post('/store',[FormController::class,'store']);

Route::get('/update/{id}',[FormController::class,'edit']);
Route::post('/update-data/{id}',[FormController::class,'update']);

Route::get('/delete/{id}',[FormController::class,'destroy']);


Route::get('/login',[LoginController::class,'login'])->name('login');
Route::post('/login-post',[LoginController::class,'userLogin']);

Route::get('/logout',[LoginController::class,'logout']);