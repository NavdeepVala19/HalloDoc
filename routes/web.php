<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\patientController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\businessRequestController;
use App\Http\Controllers\patientLoginController;
use App\Http\Controllers\patientDashboardController;
use App\Http\Controllers\patientAccountController;
use App\Http\Controllers\PatientViewDocumentsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AdminController;

// ******************************* SHIVESH **********************************************


//  ***************************************************************************************************************************************
// first page of patient site
route::get('/', [Controller::class, 'patientSite'])->name('patientSite');
//  ***************************************************************************************************************************************


//  ***************************************************************************************************************************************
// types of request 
route::get('/submit_request', function () {
    return view('patientSite/submitScreen');
})->name('submitRequest');
//  ***************************************************************************************************************************************


//  ***************************************************************************************************************************************
// patient request create
route::get('/submit_request/patient', function () {
    return view('patientSite/patientRequest');
})->name('patient');
Route::post('/patient_create', [patientController::class, 'create'])->name('patientRequests');
//  ***************************************************************************************************************************************



// Route::post('/request_create', [patientController::class, 'create'])->name('request');
//  ***************************************************************************************************************************************
// family request creating
route::get('/submit_request/family', function () {
    return view('patientSite/familyRequest');
})->name('family');
Route::post('/family_create', [familyRequestController::class, 'create'])->name('familyRequests');
//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// concierge request creating
route::get('/submit_request/concierge', function () {
    return view('patientSite/conciergeRequest');
})->name('concierge');
Route::post('/concierge_create', [conciergeRequestController::class, 'create'])->name('conciergeRequests');
//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// business request creating
route::get('/submit_request/business', function () {
    return view('patientSite/businessRequest');
})->name('business');
Route::post('/business_create', [businessRequestController::class, 'create'])->name('businessRequests');
//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// patient login page
route::get('/patient_login', [patientLoginController::class, 'loginScreen'])->name('loginScreen');
route::post('/patient_logged_in', [patientLoginController::class, 'userLogin'])->name('patient_logged_in');

route::post('/patient_login', [patientLoginController::class, 'logout'])->name('logout');
//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// to reset password of patient
route::get('/forgot_password', [patientLoginController::class, 'resetpassword'])->name('forgot_password');
route::post('/forgot_password_link', [patientLoginController::class, 'submitForgetPasswordForm'])->name('forgot.password');

Route::get('reset-password/{token}', [patientLoginController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('reset-password', [patientLoginController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//  ***************************************************************************************************************************************




//  ***************************************************************************************************************************************
// patient dashboard

// ->middleware('auth')      attach this code with below route code 
// route::get('/patientDashboard', [patientDashboardController::class,'patientDashboard'])->name('patientDashboard');   
route::get('/patientDashboard', [patientDashboardController::class, 'read'])->name('patientDashboardData');
//  ***************************************************************************************************************************************


//  ***************************************************************************************************************************************
// to create account of patient
route::get('/patient_register', [patientAccountController::class, 'patientRegister'])->name('patientRegister');
route::post('/patientRegistered', [patientAccountController::class, 'createAccount'])->name('patientRegistered');
//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// to create new request or someone else request from patient dashboard
route::get('/createPatientRequests', [patientDashboardController::class, 'createNewRequest'])->name('createPatientRequests');
route::post('/createdPatientRequests', [patientDashboardController::class, 'createNewPatient'])->name('createdPatientRequests');


route::get('/createSomeoneRequests', [patientDashboardController::class, 'createSomeoneRequest'])->name('createSomeoneRequests');
route::post('/createdSomeoneRequests', [patientDashboardController::class, 'createNewPatient'])->name('createdSomeoneRequests');
//  ***************************************************************************************************************************************




//  ***************************************************************************************************************************************
// to view documents 
route::get('/patientViewDocsFile', [PatientViewDocumentsController::class, 'patientViewDocument'])->name('patientViewDocsFile');
route::post('/patientViewDocuments', [PatientViewDocumentsController::class, 'uploadDocs'])->name('patientViewDocuments');

Route::get('/download/{filename}', [PatientViewDocumentsController::class, 'download'])->name('download');

//  ***************************************************************************************************************************************





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
Route::get('/provider/search/{status?}/{category?}', [ProviderController::class, 'search'])->name('provider-searching');

// ************** PROVIDER CREATE REQUEST PAGE ***************
// show Create request page for provider
Route::get('/create-request-provider', [ProviderController::class, 'viewCreateRequest'])->name('provider-create-request');

// Data from Create request page for Provider
Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider-request-data");

// ************** DIFFERENT ACTIONS FROM ACTION MENU ***************
// VIEW NOTES PAGE
// show view notes page as per the id
Route::get('/provider-view-notes/{id?}', [ProviderController::class, 'viewNote'])->name('provider-view-notes');

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
// Route::get('provider-view-case/{id?}', [ProviderController::class, 'viewCase'])->name('provider-view-case');
Route::get('provider/patient/view/{id}', [ProviderController::class, 'viewCase'])->name('provider-view-case');

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






// ************** ADMIN DASHBOARD (LISTING, SEARCHING & FILTERING) ***************

// For Testing Purpose only
Route::get('/test', function () {
    return view('adminPage.adminTabs.adminNewListing');
});

// Admin Dashboard page with New Users case listing
Route::get('/admin', function () {
    return redirect('/admin/new');
})->name('admin-dashboard');

// For Filtering the request for admin dashboard
Route::get('/admin/{status}/{category}', [AdminController::class, 'filter'])->name("admin-listing");

// Different status routing
Route::get('/admin/{status}', [AdminController::class, 'status'])->name("admin-status");

// For Searching Request
Route::get('/search/{status?}/{category?}', [AdminController::class, 'search'])->name('searching');
