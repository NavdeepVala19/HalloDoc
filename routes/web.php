<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\patientController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\businessRequestController;
use App\Http\Controllers\patientLoginController;
use App\Http\Controllers\patientDashboardController;
use App\Http\Controllers\patientAccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;



// ******************************* SHIVESH **********************************************
route::get('/', [Controller::class,'patientSite'])->name('patientSite');

route::get('/submit_request', function(){ return view ('patientSite/submitScreen');})->name('submitRequest');

route::get('/submit_request/patient', function() { return view('patientSite/patientRequest');})->name('patient');
Route::post('/patient_create', [patientController::class, 'create'])->name('patientRequests');

// Route::post('/request_create', [patientController::class, 'create'])->name('request');

route::get('/submit_request/family', function() { return view('patientSite/familyRequest');})->name('family');
Route::post('/family_create', [familyRequestController::class, 'create'])->name('familyRequests');


route::get('/submit_request/concierge', function() { return view('patientSite/conciergeRequest');})->name('concierge');
Route::post('/concierge_create', [conciergeRequestController::class, 'create'])->name('conciergeRequests');


route::get('/submit_request/business', function() { return view('patientSite/businessRequest');})->name('business');
Route::post('/business_create', [businessRequestController::class, 'create'])->name('businessRequests');


route::get('/patient_login', [patientLoginController::class,'loginScreen'])->name('loginScreen');
route::post('/patient_logged_in', [patientLoginController::class,'userLogin'])->name('patient_logged_in');


// ->middleware('auth')      attach this code with below route code 
route::get('/patientDashboard', [patientDashboardController::class,'patientDashboard'])->name('patientDashboard');   


route::get('/patientViewDocuments', [patientDashboardController::class,'patientViewDocument'])->name('patientViewDocs');


route::get('/patient_register', [patientAccountController::class,'patientRegister'])->name('patientRegister');
route::post('/patientRegistered', [patientAccountController::class,'createAccount'])->name('patientRegistered');




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
Route::get('/search/{status?}/{category?}', [ProviderController::class, 'search'])->name('searching');

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

// Testing Purpose
Route::get('/test', function () {
    return view('providerPage.TestView.viewUploads');
});
