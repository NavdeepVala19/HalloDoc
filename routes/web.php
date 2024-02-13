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
Route::get('/provider', function () {
    return redirect('/provider/new');
})->name('provider-dashboard');

// For Filtering the request
Route::get('/provider/{status}/{category}', [ProviderController::class, 'filter'])->name("provider-listing");

// Different status routing
Route::get('/provider/{status}', [ProviderController::class, 'status'])->name("provider-status");

// For Searching Request
// Route::get('/provider/search/{status?}', [ProviderController::class, 'search'])->name('searching');

Route::get('/search/{status?}/{category?}', [ProviderController::class, 'search'])->name('searching');

// Route::get('provider/{status}/{category?}', [ProviderController::class, 'filter'])->name('searching');
// Route::get('/provider/{status?}', [ProviderController::class, 'filter'])->name('searching');
// Route::get('provider/{status}/src', [ProviderController::class, 'search'])->name('searching');


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

// Provider Profile page
Route::get('/profile', function () {
    return view("providerPage.providerProfile");
})->name('provider-profile');
