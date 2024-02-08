<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
// Providers Dashboard page with New Users case listing
Route::get('/provider', [UserController::class, 'newUserCase'])->name("provider-dashboard");

<<<<<<< HEAD
Route::get('/create', function () {
    return view('providerPage/providerRequest');
})->name('provider-create-request');
Route::get('/', function () {
    return view('patientRequest');
});
Route::get('/create', function(){
    return view('providerRequest');
})->name('provider-create-request');
=======
route::get('/', [Controller::class,'submitRequest'])->name('submitRequest');
>>>>>>> shivesh
