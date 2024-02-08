<?php

use App\Http\Controllers\Controller;
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

route::get('/', [Controller::class,'submitRequest'])->name('submitRequest');

route::get('/patient', function() { return view('patientSite/patientRequest');})->name('patient');
route::get('/family', function() { return view('patientSite/familyRequest');})->name('family');
route::get('/conceirge', function() { return view('patientSite/conciergeRequest');})->name('conceirge');
route::get('/business', function() { return view('patientSite/businessRequest');})->name('business');


