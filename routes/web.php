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
Route::get('/search/{status?}/{category?}', [ProviderController::class, 'search'])->name('searching');

// Create request page for provider
Route::get('/create', function () {
    return view('providerPage/providerRequest');
})->name('provider-create-request');

// show view notes section
Route::get('/view-notes/{id?}', [ProviderController::class, 'viewNote'])->name('view-notes');

// View Uploads 
Route::get('/view-uploads/{id?}', [ProviderController::class, 'viewUpload'])->name('view-upload');
Route::post('/view-uploads/{id?}', [ProviderController::class, 'uploadDocument'])->name('view-upload');

// download document uploaded in view Uploads
Route::get('/download/{id?}', [ProviderController::class, 'download'])->name('download');

// Delete document
Route::get('/delete-document/{id?}', [ProviderController::class, 'deleteDoc'])->name('document.delete');

// Operations on ViewUploads page (Download All, Delete All)
Route::post('/operations', [ProviderController::class, 'operations'])->name('operations');

// show view case section
Route::get('/view-case/{id?}', [ProviderController::class, 'viewCase'])->name('view-case');

// Data from Create request page for Provider
Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider-request-data");


// when consult is selected from the encounter of active listing perform operation
Route::get('/encounter', [ProviderController::class, 'encounter'])->name("encounter");



// Encounter Form provider
Route::get(
    '/encounter-form/{id?}',
    [ProviderController::class, 'encounterFormView']
)->name('encounter-form');
Route::post('/medical-form', [ProviderController::class, 'encounterForm'])->name('encounter-form-data');

// Generater Pdf on click
Route::get('encounter-form/generate-pdf/{id?}', [ProviderController::class, 'generatePDF'])->name('generate-pdf');

// Send Email
Route::post('/send-mail', [ProviderController::class, 'sendMail'])->name('send-mail');

// Provider Profile page
Route::get('/profile', function () {
    return view("providerPage.providerProfile");
})->name('provider-profile');

// Testing Purpose
Route::get('/test', function () {
    return view('providerPage.TestView.closeCase');
});
