<?php

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\patientController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\patientLoginController;
use App\Http\Controllers\AdminProviderController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\patientAccountController;
use App\Http\Controllers\patientProfileController;
use App\Http\Controllers\businessRequestController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\patientDashboardController;
use App\Http\Controllers\PatientViewDocumentsController;

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
route::post('/patientloggedIn', [patientLoginController::class, 'userLogin'])->name('patient_logged_in');

// route::get('/pat')

route::post('/patient_logout', [patientLoginController::class, 'logout'])->name('logout');
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
route::get('/patientDashboard', [patientDashboardController::class, 'read'])->name('patientDashboardData');
//  ***************************************************************************************************************************************




//  ***************************************************************************************************************************************
// to edit profile of patient
// Route::get('/patient_profile', [patientProfileController::class, 'profile'])->name('patientProfile');

route::get('/patientProfile', [patientProfileController::class, 'patientEdit'])->name('patientProfile');
route::post('/patientProfileUpdated', [patientProfileController::class, 'patientUpdate'])->name('patientProfileEdited');

//  ***************************************************************************************************************************************





//  ***************************************************************************************************************************************
// to create account of patient
route::get('/patient_register', [patientAccountController::class, 'patientRegister'])->name('patientRegister');
route::post('/patientDashboard', [patientAccountController::class, 'createAccount'])->name('patientRegistered');
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

route::get('/downloadOne/{id}', [PatientViewDocumentsController::class, 'downloadOne'])->name('downloadOne');


route::post('/patientViewDocsDownload', [PatientViewDocumentsController::class, 'downloadSelectedFiles'])->name('downloadAllFiles');


//  ***************************************************************************************************************************************



//  ***************************************************************************************************************************************
// it will show agreement page
route::get('/patientAgreement', [patientDashboardController::class, 'viewAgreement'])->name('patientAgreement');
//  ***************************************************************************************************************************************





// **********************************************************ADMIN***************************************************************

// admin LogIn
route::get('/adminLogin', [AdminLoginController::class, 'adminLogin'])->name('adminLogin');
route::post('/adminLoggedIn', [AdminLoginController::class, 'userLogin'])->name('adminLoggedIn');


// admin ResetPassword
route::get('/adminResetPassword', [AdminLoginController::class, 'adminResetPassword'])->name('adminresetpassword');
route::post('/resetPasswordlink', [AdminLoginController::class, 'submitForgetPasswordForm'])->name('adminForgotPassword');


// admin Update Password
Route::get('updatePassword/{token}', [AdminLoginController::class, 'showUpdatePasswordForm'])->name('updatePassword');
Route::post('updatedPassword', [AdminLoginController::class, 'submitUpdatePasswordForm'])->name('updatePasswordPost');


// route::post('/admin/send-sms',[AdminDashboardController::class,'sendSMS'])->name('sendingSMS');

route::get('/admin/providers', [AdminProviderController::class, 'readProvidersInfo'])->name('adminProvidersInfo');

route::post('/admin/provider/{id}', [AdminProviderController::class, 'sendMailToContactProvider'])->name('sendMailToProvider');

route::get('/admin/new-provider', [AdminProviderController::class, 'newProvider'])->name('adminNewProvider');
route::post('/admin/new-provider', [AdminProviderController::class, 'adminCreateNewProvider'])->name('adminCreateNewProvider');


route::get('/admin/edit-provider/{id}', [AdminProviderController::class, 'editProvider'])->name('admineditProvider');
route::post('/admin/provider-updated/{id}', [AdminProviderController::class, 'updateAdminProviderProfile'])->name('adminUpdatedProvider');


route::get('/admin/providers-details/{id}', [AdminProviderController::class, 'deleteProviderAccount'])->name('deleteProviderAccount');
// route::get('/admin/providers', [AdminProviderController::class, 'providersInfo'])->name('adminProvidersInfo');



route::get('/admin/new/exportNew', [ExcelController::class, 'exportNewData'])->name('exportNewData');
route::get('/admin/new/exportPending', [ExcelController::class, 'pendingDataExport'])->name('exportPending');
route::get('/admin/new/exportActive', [ExcelController::class, 'activeDataExport'])->name('exportActive');
route::get('/admin/new/exportConclude', [ExcelController::class, 'concludeDataExport'])->name('exportConclude');
route::get('/admin/new/exportToClose', [ExcelController::class, 'toCloseDataExport'])->name('exportToClose');
route::get('/admin/new/exportUnPaid', [ExcelController::class, 'unPaidDataExport'])->name('exportUnPaid');
route::get('/admin/new/exportAll', [ExcelController::class, 'exportAll'])->name('exportAll');


route::get('/admin/createRequest', [AdminDashboardController::class, 'createNewRequest'])->name('adminPatientRequest');
route::post('/admin/createRequest', [AdminDashboardController::class, 'createAdminPatientRequest'])->name('adminCreatedPatientRequest');


route::get('/admin-new', [AdminController::class, 'fetchRegions']);

route::POST('/dropdown-data/', [AdminController::class, 'filterPatientByRegion'])->name("filterByRegion");



route::get('/admin/providerLocation', [AdminProviderController::class, 'providerLocation'])->name('providerLocation');


route::get('/admin/profile', [AdminDashboardController::class, 'adminProfile']);

// ****************************************************************************************************************************







// ******************************* NAVDEEP **********************************************

// ************** PROVIDER DASHBOARD (LISTING, SEARCHING & FILTERING) ***************
// Providers Dashboard page with New Users case listing
Route::get('/provider', [ProviderController::class, 'providerDashboard'])->name('provider.dashboard');

// For Filtering the request
Route::get('/provider/{status}/{category}', [ProviderController::class, 'filter'])->name("provider.listing");

// Different status routing
Route::get('/provider/{status}', [ProviderController::class, 'status'])->name("provider.status");

// For Searching Request
Route::get('/provider/search/{status?}/{category?}', [ProviderController::class, 'search'])->name('provider.searching');

// ************** PROVIDER CREATE REQUEST PAGE ***************
// show Create request page for provider
Route::get('/create-request-provider', [ProviderController::class, 'viewCreateRequest'])->name('provider.create.request');

// Data from Create request page for Provider
Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider.request.data");

// ************** DIFFERENT ACTIONS FROM ACTION MENU ***************
// Accept Case by provider
Route::get('/accept-case/{id}', [ProviderController::class, 'acceptCase'])->name('provider.accept.case');

Route::post('/transfer-case', [ProviderController::class, 'assignCase'])->name('provider.transfer.case');

// VIEW NOTES PAGE
// show view notes page as per the id
Route::get('/provider-view-notes/{id?}', [ProviderController::class, 'viewNote'])->name('provider.view.notes');

// VIEW UPLOADS PAGE
// View Uploads (currently showing all the documents in requestWiseFile table)
Route::get('/view-uploads/{id?}', [ProviderController::class, 'viewUpload'])->name('provider.view.upload');
Route::post('/view-uploads/{id?}', [ProviderController::class, 'uploadDocument']);

// download document uploaded in view Uploads
Route::get('/download/{id?}', [ProviderController::class, 'download'])->name('download');

// Delete document
Route::get('/delete-document/{id?}', [ProviderController::class, 'deleteDoc'])->name('document.delete');

// Operations on ViewUploads page (Download All, Delete All)
Route::post('/operations', [ProviderController::class, 'operations'])->name('operations');

// VIEW CASE PAGE  
// show view case page as per the id
// Route::get('provider-view-case/{id?}', [ProviderController::class, 'viewCase'])->name('provider-view-case');
Route::get('provider/view/case/{id?}', [ProviderController::class, 'viewCase'])->name('provider.view.case');

// VIEW SEND ORDER PAGE
// Send Order active state provider
Route::get('/view-order/{id?}', [ProviderController::class, 'viewOrder'])->name('provider.view.order');


// SEND LINK DASHBOARD PAGE
// Send Agreement via email and sms, pending page
Route::post('/send-agreement', [ProviderController::class, 'sendAgreementLink'])->name('send.agreement');


// when consult is selected from the encounter of active listing perform operation
Route::get('/encounter', [ProviderController::class, 'encounter'])->name("encounter");

// ENCOUNTER FORM
// Show Encounter Form when clicked on Encounter from Conclude State
Route::get(
    '/encounter-form/{id?}',
    [ProviderController::class, 'encounterFormView']
)->name('provider.encounter.form');

// Data of the medical-form (encounter-form) -> Create data if no previous entries done, otherwise update form with current data
Route::post('/medical-form', [ProviderController::class, 'encounterForm'])->name('encounter.form.data');

// Generate Pdf of the medical-form when finalized (IMPLEMENTATION REMAINING - once finalized, generate pdf and then the form should not be visible again, option to download the form)
Route::get('encounter-form/generate-pdf/{id?}', [ProviderController::class, 'generatePDF'])->name('generate.pdf');

// Send Email for creating request through provider
Route::post('/provider/send-mail', [ProviderController::class, 'sendMail'])->name('send.mail');

// Provider Profile page (MyProfile)
Route::get('/profile', [ProviderController::class, 'providerProfile'])->name('provider.profile');








// ************** ADMIN DASHBOARD (LISTING, SEARCHING & FILTERING) ***************
// Admin Dashboard page with New Users case listing
Route::get('/admin', function () {
    return redirect('/admin/new');
})->name('admin.dashboard');

// For Filtering the request for admin dashboard
Route::get('/admin/{status}/{category}', [AdminController::class, 'adminFilter'])->name("admin.listing");

// Different status routing
Route::get('/admin/{status}', [AdminController::class, 'status'])->name("admin.status");

// For Searching Request
Route::get('/search/{status?}/{category?}', [AdminController::class, 'search'])->name('searching');

// Assign Case pop-up, populate select menu with all physician regions (AJAX)
Route::get('/physician-regions', [AdminController::class, 'physicianRegions'])->name('physician.regions');
Route::get('/physician/{id}', [AdminController::class, 'getPhysicians'])->name('get.physician');

Route::post('/assign-case', [AdminController::class, 'assignCase'])->name('admin.assign.case');
Route::post('/transfer-case-admin', [AdminController::class, 'transferCase'])->name('admin.transfer.case');

// Send Link
Route::post('/admin/send-mail', [AdminController::class, 'sendMail'])->name('admin.send.mail');


// Cancel Case by admin
Route::get('/cancel-case', [AdminController::class, "cancelCaseOptions"]);
Route::post('cancel-case-data', [AdminController::class, 'cancelCase'])->name('admin.cancel.case');

// Block Case by admin
Route::post('block-case', [AdminController::class, 'blockCase'])->name('admin.block.case');

// Admin View Case
Route::get('admin/view/case/{id?}', [AdminController::class, 'viewCase'])->name('admin.view.case');


// Clear Case by admin pending and close state
Route::post('clear-case', [AdminController::class, 'clearCase'])->name('admin.clear.case');

// close case admin
Route::get('/close-case/{id}', [AdminController::class, 'closeCase'])->name('admin.close.case');
Route::post('/close-case', [AdminController::class, 'closeCaseData'])->name('admin.close.case.save');


// Partners Page in Admin
Route::get('/partners/{id?}', [AdminController::class, 'viewPartners'])->name('admin.partners');
// Search Vendors/Partners
Route::post('/search-partners', [AdminController::class, 'searchPartners'])->name('search.partners');

// Add Business Page
Route::get('/add-business', [AdminController::class, 'addBusinessView'])->name('add.business.view');
Route::post('/add-business', [AdminController::class, 'addBusiness'])->name('add.business');
// Update Business Page
Route::get('/update-business/{id}', [AdminController::class, 'updateBusinessView'])->name('update.business.view');
Route::post('/update-business', [AdminController::class, 'updateBusiness'])->name('update.business');
Route::get('/delete-business/{id}', [AdminController::class, 'deleteBusiness'])->name('delete.business');

// send orders admin page 
Route::get('/admin-view-order/{id}', [AdminController::class, 'viewOrder'])->name('admin.view.order');
Route::post('/admin-send-order', [AdminController::class, 'sendOrder'])->name('admin.send.order');
// Dynamically update data of business dropdown based on selection of profession
Route::get('/fetch-business/{id}', [AdminController::class, 'fetchBusiness'])->name('fetch.business');
// Dynamically fetch data of business based on selection in dropdown
Route::get('/fetch-business-data/{id}', [AdminController::class, 'fetchBusinessData'])->name('fetch.business.data');

// Account Roles Access Page
Route::get('/access', [AdminController::class, 'accessView'])->name('admin.access.view');
Route::get('/create-role', [AdminController::class, 'createRoleView'])->name('admin.create.role.view');
Route::get('/fetch-roles/{id}', [AdminController::class, 'fetchRoles'])->name('fetch.roles');
Route::post('/create-access', [AdminController::class, 'createAccess'])->name('admin.create.access');
Route::get('/delete-access/{id}', [AdminController::class, 'deleteAccess'])->name('admin.access.delete');
Route::get('/edit-access/{id}', [AdminController::class, 'editAccess'])->name('admin.edit.access');
Route::get('/user-access', [AdminController::class, 'UserAccess'])->name('admin.user.access');

// Records Page 
Route::get('/search-records', [AdminController::class, 'searchRecordsView'])->name('admin.search.records.view');
Route::get('/email-logs', [AdminController::class, 'emailRecordsView'])->name('admin.email.records.view');
Route::post('/email-logs', [AdminController::class, 'searchEmail'])->name('search.filter.email');

Route::get('/sms-logs', [AdminController::class, 'smsRecordsView'])->name('admin.sms.records.view');
Route::get('/block-history', [AdminController::class, 'blockHistoryView'])->name('admin.block.history.view');
Route::get('/patient-history', [AdminController::class, 'patientHistoryView'])->name('admin.patient.records.view');
Route::post('/search-patient-data', [AdminController::class, 'searchPatientData'])->name('admin.search.patient');
Route::get('/patient-records/{id}', [AdminController::class, 'patientRecordsView'])->name('patient.records');


// Cancel History Page
Route::get('/cancel-history', [AdminController::class, 'viewCancelHistory'])->name('admin.cancel.history.view');
Route::post('/cancel-history', [AdminController::class, 'searchCancelCase'])->name('cancel.case.search');

// Scheduling
// Scheduling Calendar view 
Route::get('/scheduling', [SchedulingController::class, 'schedulingCalendarView'])->name('scheduling');
Route::get('/provider-data', [SchedulingController::class, 'providerData'])->name('provider.data');
// Providers on call view
Route::get('/providers-on-call', [SchedulingController::class, 'providersOnCall'])->name('providers.on.call');
// Shifts for Review view
Route::get('/shifts-review', [SchedulingController::class, 'shiftsReviewView'])->name('shifts.review');

// For Testing Purpose only
Route::get('/test', function () {
    return view('adminPage.scheduling.scheduling');
});
