<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\patientController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\businessRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Models\Provider;

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

// ************** PROVIDER DASHBOARD (LISTING, SEARCHING & FILTERING) ***************
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

// ************** PROVIDER CREATE REQUEST PAGE ***************
// show Create request page for provider
Route::get('/create', [ProviderController::class, 'viewCreateRequest'])->name('provider-create-request');

// Data from Create request page for Provider
Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider-request-data");

// ************** DIFFERENT ACTIONS FROM ACTION MENU ***************
// VIEW NOTES PAGE
// show view notes page as per the id
Route::get('/view-notes/{id?}', [ProviderController::class, 'viewNote'])->name('view-notes');

// VIEW UPLOADS PAGE
// View Uploads (currently showing all the documents in requestWiseFile table)
Route::get('/view-uploads/{id?}', [ProviderController::class, 'viewUpload'])->name('view-upload');
Route::post('/view-uploads/{id?}', [ProviderController::class, 'uploadDocument'])->name('view-upload');

// download document uploaded in view Uploads
Route::get('/download/{id?}', [ProviderController::class, 'download'])->name('download');

// Delete document
Route::get('/delete-document/{id?}', [ProviderController::class, 'deleteDoc'])->name('document.delete');

// Operations on ViewUploads page (Download All, Delete All)
Route::post('/operations', [ProviderController::class, 'operations'])->name('operations');

// VIEW CASE PAGE  
// show view case page as per the id
Route::get('/view-case/{id?}', [ProviderController::class, 'viewCase'])->name('view-case');

// VIEW SEND ORDER PAGE
// Send Order active state provider
Route::get('/view-order/{id?}', [ProviderController::class, 'viewOrder'])->name('view-order');


// SEND LINK DASHBOARD PAGE
// Send Agreement via email and sms, pending page
Route::post('/send-agreement', [ProviderController::class, 'sendAgreementLink'])->name('send-agreement');


// when consult is selected from the encounter of active listing perform operation
Route::get('/encounter', [ProviderController::class, 'encounter'])->name("encounter");

// ENCOUNTER FORM
// Show Encounter Form when clicked on Encounter from Conclude State
Route::get(
    '/encounter-form/{id?}',
    [ProviderController::class, 'encounterFormView']
)->name('encounter-form');

// Data of the medical-form (encounter-form) -> Create data if no previous entries done, otherwise update form with current data
Route::post('/medical-form', [ProviderController::class, 'encounterForm'])->name('encounter-form-data');

// Generate Pdf of the medical-form when finalized (IMPLEMENTATION REMAINING - once finalized, generate pdf and then the form should not be visible again, option to download the form)
Route::get('encounter-form/generate-pdf/{id?}', [ProviderController::class, 'generatePDF'])->name('generate-pdf');

// Send Email for creating request through provider
Route::post('/send-mail', [ProviderController::class, 'sendMail'])->name('send-mail');

// Provider Profile page (MyProfile)
Route::get('/profile', [ProviderController::class, 'providerProfile'])->name('provider-profile');



// For Testing Purpose only
Route::get('/test', function () {
    return view('providerPage.TestView.closeCase');
});
