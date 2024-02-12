<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\patientController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\businessRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;



// ******************************* SHIVESH **********************************************
route::get('/', [Controller::class, 'submitRequest'])->name('submitRequest');

route::get('/patient', function () {
    return view('patientSite/patientRequest');
})->name('patient');
route::get('/family', function () {
    return view('patientSite/familyRequest');
})->name('family');
route::get('/conceirge', function () {
    return view('patientSite/conciergeRequest');
})->name('conceirge');
route::get('/business', function () {
    return view('patientSite/businessRequest');
})->name('business');


// ******************************* NAVDEEP **********************************************
// Providers Dashboard page with New Users case listing
Route::get('/provider', [ProviderController::class, 'listing'])->name("provider-dashboard");
Route::get('/provider/{status}', [ProviderController::class, 'status'])->name("provider-status");

// Route::get('/provider/{status?}', [ProviderController::class, 'status'])->name("provider-status");
Route::get('/provider/{status?}/{category?}', [ProviderController::class, 'filter'])->name("provider-listing");

// For Filtering Request
// Route::get('filter/{category?}', [ProviderController::class, 'filter'])->name('filter');
// For Filtering Request
Route::get('provider/{search?}', [ProviderController::class, 'filter'])->name('searching');


// Create request page for provider
Route::get('/create', function () {
    return view('providerPage/providerRequest');
})->name('provider-create-request');

// Data from Create request page for Provider
Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider-request-data");

// Encounter Form provider
Route::get(
    '/encounter-form',
    function () {
        return view('providerPage.encounterForm');
    }
)->name('encounter-form');
