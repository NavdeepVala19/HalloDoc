<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\patientController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\businessRequestController;
use App\Http\Controllers\patientLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;



// ******************************* SHIVESH **********************************************
// route::get('/', [Controller::class,'submitRequest'])->name('submitRequest');

route::get('/patient', function() { return view('patientSite/patientRequest');})->name('patient');
Route::post('/patient_create', [patientController::class, 'create'])->name('patientRequests');

// Route::post('/request_create', [patientController::class, 'create'])->name('request');

route::get('/family', function() { return view('patientSite/familyRequest');})->name('family');
Route::post('/family_create', [familyRequestController::class, 'create'])->name('familyRequests');


route::get('/concierge', function() { return view('patientSite/conciergeRequest');})->name('concierge');
Route::post('/concierge_create', [conciergeRequestController::class, 'create'])->name('conciergeRequests');


route::get('/business', function() { return view('patientSite/businessRequest');})->name('business');
Route::post('/business_create', [businessRequestController::class, 'create'])->name('businessRequests');


route::get('/', [patientLoginController::class,'loginScreen'])->name('loginScreen');




// ******************************* NAVDEEP **********************************************
// Providers Dashboard page with New Users case listing
Route::get('/provider', [ProviderController::class, 'newUserCase'])->name("provider-dashboard");

// Create request page for provider
Route::get('/create', function () {
    return view('providerPage/providerRequest');
})->name('provider-create-request');




