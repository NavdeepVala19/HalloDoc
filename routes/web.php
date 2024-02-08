<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;

// ******************************* SHIVESH **********************************************
Route::get('/', function () {
    return view('patientRequest');
});



// ******************************* NAVDEEP **********************************************
// Providers Dashboard page with New Users case listing
Route::get('/provider', [ProviderController::class, 'newUserCase'])->name("provider-dashboard");

// Create request page for provider
Route::get('/create', function () {
    return view('providerPage/providerRequest');
})->name('provider-create-request');

