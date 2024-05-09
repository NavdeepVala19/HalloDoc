<?php

use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminProviderController;
use App\Http\Controllers\businessRequestController;
use App\Http\Controllers\CommonOperationController;
use App\Http\Controllers\conciergeRequestController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\familyRequestController;
use App\Http\Controllers\patientAccountController;
use App\Http\Controllers\patientController;
use App\Http\Controllers\patientDashboardController;
use App\Http\Controllers\patientLoginController;
use App\Http\Controllers\patientProfileController;
use App\Http\Controllers\PatientViewDocumentsController;
use App\Http\Controllers\ProviderActionController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProviderSchedulingController;
use App\Http\Controllers\SchedulingController;
use Illuminate\Support\Facades\Route;

// ******************************* SHIVESH **********************************************

//  ******* First page of patient site *********
route::get('/', [Controller::class, 'patientSite'])->name('patientSite');


//  ***** Types of request ******
route::get('/submit_request', [Controller::class, 'submitScreen'])->name('submitRequest');

//  ******** Patient request create**********
route::get('/submit_request/patient', [patientController::class, 'patientRequests'])->name('patient');
Route::post('/patient_create', [patientController::class, 'create'])->name('patientRequests');

//  ****** Family request creating *********
route::get('/submit_request/family', [familyRequestController::class, 'familyRequests'])->name('family');
Route::post('/family_create', [familyRequestController::class, 'create'])->name('familyRequests');

//  ******* Concierge request creating ********
route::get('/submit_request/concierge', [conciergeRequestController::class, 'conciergeRequests'])->name('concierge');
Route::post('/concierge_create', [conciergeRequestController::class, 'create'])->name('conciergeRequests');

//  ******** Business request creating **********
route::get('/submit_request/business', [businessRequestController::class, 'businessRequests'])->name('business');
Route::post('/business_create', [businessRequestController::class, 'create'])->name('businessRequests');

//  ********* Create account of patient ***********
route::get('/patient_register', [patientAccountController::class, 'patientRegister'])->name('patientRegister');
route::post('/patientRegistered', [patientAccountController::class, 'createAccount'])->name('patientRegistered');
//  *************************************************************************************************************

//  ******  Patient login page *********
route::get('/patient_login', [patientLoginController::class, 'loginScreen'])->name('loginScreen');
route::post('/patientloggedIn', [patientLoginController::class, 'userLogin'])->name('patient_logged_in');

//  ******** Reset password of patient **********
route::get('/forgot_password', [patientLoginController::class, 'resetpassword'])->name('forgot_password');
route::post('/forgot_password_link', [patientLoginController::class, 'submitForgetPasswordForm'])->name('forgot.password');

Route::get('reset-password/{token}', [patientLoginController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('reset-password', [patientLoginController::class, 'submitResetPasswordForm'])->name('reset.password.post');

route::middleware('CheckPatientLogin')->group(function () {
    route::get('/patient_logout', [patientLoginController::class, 'logout'])->name('patientLogOut');

    //  ******* Patient Dashboard **********x
    route::get('/patientDashboard', [patientDashboardController::class, 'read'])->name('patientDashboardData');

    //  ********* Edit profile of patient ***********
    route::get('/patientProfile', [patientProfileController::class, 'patientEdit'])->name('patientProfile');
    route::get('/patient-profile-edit/{id}', [patientProfileController::class, 'patientprofileEdit'])->name('patientProfileEditData');
    route::post('/patientProfileUpdated', [patientProfileController::class, 'patientUpdate'])->name('patientProfileEdited');
    route::get('/patientMapLocation', [patientProfileController::class, 'patientMapLocation'])->name('patientLocationOnMap');

    //  ********* Create New Request or Someone else request from Patient Dashboard ********
    route::get('/createPatientRequests', [patientDashboardController::class, 'createNewRequest'])->name('createPatientRequests');
    route::post('/createdPatientRequests', [patientDashboardController::class, 'createNewPatient'])->name('createdPatientRequests');

    route::get('/createSomeoneRequests', [patientDashboardController::class, 'createSomeoneRequest'])->name('createSomeoneRequests');
    route::post('/createdSomeoneRequests', [patientDashboardController::class, 'createSomeOneElseRequest'])->name('createdSomeoneRequests');

    //  *********  View Documents  *********
    route::get('/patientViewDocsFile/{id}', [PatientViewDocumentsController::class, 'patientViewDocument'])->name('patientViewDocsFile');
    route::post('/patientViewDocuments', [PatientViewDocumentsController::class, 'uploadDocs'])->name('patientViewDocuments');
    route::get('/downloadOne/{id}', [PatientViewDocumentsController::class, 'downloadOne'])->name('downloadOne');
    route::post('/patientViewDocsDownload', [PatientViewDocumentsController::class, 'downloadSelectedFiles'])->name('downloadAllFiles');
});
//  *******************************************************************************************************

//  ***************************************************************************************************************************************
// it will show agreement page
route::get('/patientAgreement/{data}', [patientDashboardController::class, 'viewAgreement'])->name('patientAgreement');
// Agreement Agreed by patient
Route::post('/agree-agreement', [patientDashboardController::class, 'agreeAgreement'])->name('patient.agree.agreement');
// Agreement Cancelled by patient
Route::post('/cancel-agreement', [patientDashboardController::class, 'cancelAgreement'])->name('patient.cancel.agreement');
//  ***************************************************************************************************************************************

// **********************************************************ADMIN***************************************************************

// admin LogIn
route::get('/adminLogin', [AdminLoginController::class, 'adminLogin'])->name('adminLogin');
route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');

route::post('/adminLoggedIn', [AdminLoginController::class, 'userLogin'])->name('adminLoggedIn');
// route::get('/testData', [AdminLoginController::class, 'testData']);

// admin ResetPassword
route::get('/adminResetPassword', [AdminLoginController::class, 'adminResetPassword'])->name('adminresetpassword');
route::post('/resetPasswordlink', [AdminLoginController::class, 'submitForgetPasswordForm'])->name('adminForgotPassword');

// admin Update Password
Route::get('updatePassword/{token}', [AdminLoginController::class, 'showUpdatePasswordForm'])->name('updatePassword');
Route::post('updatedPassword', [AdminLoginController::class, 'submitUpdatePasswordForm'])->name('updatePasswordPost');

// route::post('/admin/send-sms',[AdminDashboardController::class,'sendSMS'])->name('sendingSMS');
// ****************************************************************************************************************************

// ******************************* NAVDEEP's WORK **********************************************
// ************** PROVIDER DASHBOARD (LISTING, SEARCHING & FILTERING) ***************
// Route::prefix('provider')->group(function () {
// });
Route::middleware('CheckProviderLogin')->group(function () {
    // Providers Dashboard page with New state case listing
    Route::get('/provider', [ProviderController::class, 'providerDashboard'])->name('provider.dashboard');

    // For Filtering the request
    Route::get('/provider/{status}/{category}', [ProviderController::class, 'filter'])->name("provider.listing");

    // Different status routing
    Route::get('/provider/{status}', [ProviderController::class, 'status'])->name("provider.status");

    // For Searching Request
    Route::post('/provider/{status?}/{category?}', [ProviderController::class, 'search'])->name('provider.searching');

    // ************** PROVIDER CREATE REQUEST PAGE ***************
    // show Create request page for provider
    Route::get('/create-request-provider', [ProviderController::class, 'viewCreateRequest'])->name('provider.create.request');

    // Data from Create request page for Provider
    Route::post('/provider-request', [ProviderController::class, 'createRequest'])->name("provider.request.data");

    // Send Email for creating request through provider
    Route::post('/provider-send-mail', [ProviderController::class, 'sendMail'])->name('provider.send.mail');

    // Provider Profile page (MyProfile)
    Route::get('/profile', [ProviderController::class, 'providerProfile'])->name('provider.profile');
    // Provider Reset Password (MyProfile)
    Route::post('/provider-reset-password', [ProviderController::class, 'resetPassword'])->name('provider.reset.password');
    // Provider Edit Profile Send message (Email) to Admin
    Route::post('/provider-edit-profile', [ProviderController::class, 'editProfileMessage'])->name('provider.edit.profile');

    // ************** DIFFERENT ACTIONS FROM ACTION MENU ***************
    // Accept Case by provider
    Route::get('/accept-case/{id}', [ProviderActionController::class, 'acceptCase'])->name('provider.accept.case');
    // Transfer Case to admin by provider
    Route::post('/transfer-case', [ProviderActionController::class, 'transferCase'])->name('provider.transfer.case');

    // VIEW NOTES PAGE
    // show view notes page as per the id
    Route::get('/provider/view/notes/{id}', [ProviderActionController::class, 'viewNote'])->name('provider.view.notes');
    // Store notes saved by provider
    Route::post('/provider/view/notes/store', [ProviderActionController::class, 'storeNote'])->name('provider.store.note');

    // VIEW UPLOADS PAGE
    // View Uploads (currently showing all the documents in requestWiseFile table)
    Route::get('/view-uploads/{id?}', [ProviderActionController::class, 'viewUpload'])->name('provider.view.upload');
    // upload document from viewUploads page
    Route::post('/view-uploads/{id?}', [ProviderActionController::class, 'uploadDocument'])->name('proivder.upload.doc');

    // VIEW CASE PAGE
    // show view case page as per the id
    Route::get('provider/view/case/{id?}', [ProviderActionController::class, 'viewCase'])->name('provider.view.case');

    // VIEW SEND ORDER PAGE
    // Send Order active state provider
    Route::get('/view-order/{id?}', [ProviderActionController::class, 'viewOrder'])->name('provider.view.order');
    // Store data from the form to the database table
    Route::post('/provider-send-order', [ProviderActionController::class, 'sendOrder'])->name('provider.send.order');

    // when consult is selected from the encounter of active listing perform operation
    Route::get('/provider-encounter', [ProviderActionController::class, 'encounter'])->name("provider.active.encounter");

    // When clicked on House Call From active page, change it's state to conlude
    Route::get('/provider-housecall-encounter/{requestId}', [ProviderActionController::class, 'encounterHouseCall'])->name('provider.houseCall.encounter');

    // ENCOUNTER FORM
    // Display Encounter Form page when clicked on Encounter from Conclude State
    Route::get(
        '/encounter-form/{id?}',
        [ProviderActionController::class, 'encounterFormView']
    )->name('provider.encounter.form');

    // Data of the medical-form (encounter-form) -> Create data if no previous entries done, otherwise update form with current data
    Route::post('/medical-form', [ProviderActionController::class, 'encounterForm'])->name('encounter.form.data');

    // Encounter (Medical Form Finalized) by Provider
    Route::get('/encounter-finalized/{id}', [ProviderActionController::class, 'encounterFinalized'])->name('encounter.finalized');

    // Download The medical-form when clicked from encounter finalized pop-up from conclude state
    Route::post('/download-medical-form', [ProviderActionController::class, 'downloadMedicalForm'])->name('provider.download.encounterForm');

    // Conclude Care Page view -> conclude state -> Provider
    Route::get('/conclude-care/{id}', [ProviderActionController::class, 'viewConcludeCare'])->name('provider.conclude.care.view');
    // Conclude Care implementation
    Route::post('/conclude-care', [ProviderActionController::class, 'concludeCare'])->name('provider.conclude.care');
    // conclude Care upload docs
    Route::post('/upload-document-conclude-care', [ProviderActionController::class, 'uploadDocsConcludeCare'])->name('upload.conclude.care.docs');

    // Provider Scheduling
    // Scheduling Calendar view
    Route::get('/provider-scheduling', [ProviderSchedulingController::class, 'providerCalendarView'])->name('provider.scheduling');
    // Provider information for add new shift
    Route::get('/provider-information', [ProviderSchedulingController::class, 'providerInformation'])->name('provider.information');
    // Provider created Shift data
    Route::post('/provider-create-shift', [ProviderSchedulingController::class, 'providerShiftData'])->name('physician.scheduling.data');
    // Provider shift data
    Route::get('/provider-shift', [ProviderSchedulingController::class, 'providerShift'])->name('provider.shift');
    // Provider Edit Shift
    Route::post('/provider-edit-shift', [ProviderSchedulingController::class, 'providerEditShift'])->name('provider.edit.shift');
});

// ************** ADMIN DASHBOARD (LISTING, SEARCHING & FILTERING) ***************
// Admin Dashboard page with New Users case listing
Route::middleware('CheckAdminLogin')->group(function () {
    // Redirect to default new page when hit with just /admin in route
    Route::get('/admin', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // For Filtering the request for admin dashboard
    Route::get('/admin/{status}/{category}', [AdminController::class, 'adminFilter'])->name("admin.listing");

    // Different status routing
    Route::get('/admin/{status}', [AdminController::class, 'status'])->name("admin.status");

    // For Searching Request
    // Route::post('admin/search/{status?}/{category?}', [AdminController::class, 'search'])->name('searching');
    Route::post('admin/{status?}/{category?}', [AdminController::class, 'search'])->name('searching');

    // Send Link
    Route::post('/admin/send/mail/patient', [AdminController::class, 'sendMail'])->name('admin.send.mail');

    // Assign Case pop-up, populate select menu with all physician regions (AJAX)
    Route::get('/physician-regions', [AdminActionController::class, 'physicianRegions'])->name('physician.regions');
    // Get particular physicians as per the region selected from dropdown
    Route::get('/physician/{id}', [AdminActionController::class, 'getPhysicians'])->name('get.physician');

    // Get all physicians other than the one who transferred the case
    Route::get('/newPhysicians/{requestId}/{regionId}', [AdminActionController::class, 'getNewPhysicians'])->name('get.new.physician');

    // Admin assign case -> to particular physician
    Route::post('/assign-case', [AdminActionController::class, 'assignCase'])->name('admin.assign.case');
    // Admin transfer case -> to physician other than the one who transferred case
    Route::post('/transfer-case-admin', [AdminActionController::class, 'transferCase'])->name('admin.transfer.case');

    // Fetch Cancel Case (CaseTag) options from database
    Route::get('/cancel-case', [AdminActionController::class, "cancelCaseOptions"]);
    // Cancel Case by admin
    Route::post('cancel-case-data', [AdminActionController::class, 'cancelCase'])->name('admin.cancel.case');

    // Block Case by admin
    Route::post('block-case', [AdminActionController::class, 'blockCase'])->name('admin.block.case');

    // Admin View Case
    Route::get('admin/view/case/{id}', [AdminActionController::class, 'viewCase'])->name('admin.view.case');
    // Admin Edit Case
    Route::post('admin/view/case/edit', [AdminActionController::class, 'editCase'])->name('admin.edit.case');

    // Admin View Notes
    Route::get('admin/view/notes/{id}', [AdminActionController::class, 'viewNote'])->name('admin.view.note');
    // Store Additional Note entered by Admin
    Route::post('/admin/view/notes/store', [AdminActionController::class, 'storeNote'])->name('admin.store.note');

    // Admin View Uploads
    Route::get('admin/view/uploads/{id}', [AdminActionController::class, 'viewUpload'])->name('admin.view.upload');
    // Admin upload document from viewUploads page
    Route::post('admin/view/uploads/{id?}', [AdminActionController::class, 'uploadDocument'])->name('admin.upload.doc');

    // ENCOUNTER FORM
    // Show Encounter Form when clicked on Encounter from Conclude State
    Route::get('/admin-encounter-form/{id?}', [AdminActionController::class, 'encounterFormView'])->name('admin.encounter.form');

    // Changes Saved from admin on Medical Form(Encounter Form)
    Route::post('/admin-medical-form', [AdminActionController::class, 'encounterForm'])->name('admin.medical.data');

    // Download Encounter form on clicking view button
    Route::get('/download-encounter-form/{requestId}', [AdminActionController::class, 'downloadEncounterForm'])->name('download.encounter.form');

    // Clear Case by admin pending and close state
    Route::post('clear-case', [AdminActionController::class, 'clearCase'])->name('admin.clear.case');

    // close case admin
    Route::get('/close-case/{id}', [AdminActionController::class, 'closeCase'])->name('admin.close.case');
    // admin closes case -> store the fetched data from form and change status for that particular request
    Route::post('/close-case', [AdminActionController::class, 'closeCaseData'])->name('admin.close.case.save');

    // send orders admin page
    Route::get('/admin-view-order/{id}', [AdminActionController::class, 'viewOrder'])->name('admin.view.order');
    // Admin send order data stored in database table
    Route::post('/admin-send-order', [AdminActionController::class, 'sendOrder'])->name('admin.send.order');

    // Partners Page in Admin
    Route::get('/partners/{id?}', [AdminController::class, 'viewPartners'])->name('admin.partners');
    // Search Vendors/Partners
    Route::get('/search-partners', [AdminController::class, 'searchPartners'])->name('search.partners');

    // Add Business Page
    Route::get('/add-business', [AdminController::class, 'addBusinessView'])->name('add.business.view');
    // add a new business from addBusiness page
    Route::post('/add-business', [AdminController::class, 'addBusiness'])->name('add.business');
    // Update Business Page
    Route::get('/update-business/{id}', [AdminController::class, 'updateBusinessView'])->name('update.business.view');
    // Update already existing business details
    Route::post('/update-business', [AdminController::class, 'updateBusiness'])->name('update.business');
    // Delete already existing business
    Route::get('/delete-business/{id}', [AdminController::class, 'deleteBusiness'])->name('delete.business');

    // Account Access Page
    Route::get('/access', [AdminController::class, 'accessView'])->name('admin.access.view');
    // Create a new role page
    Route::get('/create-role', [AdminController::class, 'createRoleView'])->name('admin.create.role.view');
    // fetch all roles to show in the dropdown as per the account type
    Route::get('/fetch-roles/{id}', [AdminController::class, 'fetchRoles'])->name('fetch.roles');
    // create a new access, and store that data
    Route::post('/create-access', [AdminController::class, 'createAccess'])->name('admin.create.access');
    // Delete already existing access
    Route::get('/delete-access/{id}', [AdminController::class, 'deleteAccess'])->name('admin.access.delete');
    // Display Edit access page
    Route::get('/edit-access/{id}', [AdminController::class, 'editAccess'])->name('admin.edit.access');
    // Edit an already existing access
    Route::post('/edit-access-data', [AdminController::class, 'editAccessData'])->name('admin.edit.access.data');

    // Email Logs Page
    Route::get('/email-logs', [AdminController::class, 'emailRecordsView'])->name('admin.email.records.view');
    // Search and filter email logs page
    Route::get('/search-email-logs', [AdminController::class, 'searchEmail'])->name('search.filter.email');
    // Patient History Page
    Route::get('/patient-history', [AdminController::class, 'patientHistoryView'])->name('admin.patient.records.view');
    // searching and filtering of Patient Records Page
    Route::get('/search-patient-data', [AdminController::class, 'searchPatientData'])->name('admin.search.patient');
    // Display Patient Records Page
    Route::get('/patient-records/{id}', [AdminController::class, 'patientRecordsView'])->name('patient.records');

    // ---------------------------- SCHEDULING ----------------------------
    // Admin Scheduling
    // Scheduling Calendar view
    Route::get('/scheduling', [SchedulingController::class, 'schedulingCalendarView'])->name('admin.scheduling');
    // Scheduling Filter by region
    Route::get('/scheduling/region/{id}', [SchedulingController::class, 'shiftFilter'])->name('admin.scheduling.filter');
    Route::get('/provider-data', [SchedulingController::class, 'providerData'])->name('provider.data');
    // Providers on call view
    Route::get('/providers-on-call', [SchedulingController::class, 'providersOnCall'])->name('providers.on.call');
    // Filter Providers based on region selected for Providers on Call Page
    Route::get('/filterProvidersByRegion/{id}', [SchedulingController::class, 'filterProviderByRegion'])->name('filter.providers.by.region');
    // Shifts for Review view
    Route::get('/shifts-review', [SchedulingController::class, 'shiftsReviewView'])->name('shifts.review');
    // Create Shift data
    Route::post('/create-shift', [SchedulingController::class, 'createShiftData'])->name('admin.scheduling.data');
    // Events data
    Route::get('/events-data', [SchedulingController::class, 'eventsData'])->name('events.data');
    // Edit Shifts
    Route::post('/admin-edit-shift', [SchedulingController::class, 'editShift'])->name('admin.edit.shift');
    // Approve or Delete Selected shifts from shifts-review Page
    Route::post('/shift-action', [SchedulingController::class, 'shiftAction'])->name('admin.shifts.review');
    // Filter Shifts review page as per the region selected
    Route::post('/filter-regions', [SchedulingController::class, 'filterRegions'])->name('filter-regions-shifts');

    // ------------------------ Shivesh Work -----------------------------------
    route::get('/admin-providers', [AdminProviderController::class, 'readProvidersInfo'])->name('adminProvidersInfo');

    route::post('/admin-send-msg-provider/{id}', [AdminProviderController::class, 'sendMailToContactProvider'])->name('sendMailToProvider');

    route::get('/admin/new-provider', [AdminProviderController::class, 'newProvider'])->name('adminNewProvider');
    route::post('/admin/new-provider', [AdminProviderController::class, 'adminCreateNewProvider'])->name('adminCreateNewProvider');

    route::get('/admin-new-provider', [AdminProviderController::class, 'newProvider'])->name('adminNewProvider');
    route::post('/admin-new-provider', [AdminProviderController::class, 'adminCreateNewProvider'])->name('adminCreateNewProvider');

    route::get('/admin-edit-provider/{id}', [AdminProviderController::class, 'editProvider'])->name('adminEditProvider');

    route::post('/admin-provider-updated-account/{id}', [AdminProviderController::class, 'updateProviderAccountInfo'])->name('updateProviderAccountInfo');
    route::post('/admin-provider-updated-info/{id}', [AdminProviderController::class, 'providerInfoUpdate'])->name('providerInfoUpdate');
    route::post('/admin-provider-updated-mail-info/{id}', [AdminProviderController::class, 'providerMailInfoUpdate'])->name('providerMailInfoUpdate');
    route::post('/admin-provider-updated-profile-data/{id}', [AdminProviderController::class, 'providerProfileUpdate'])->name('providerProfileUpdate');
    route::post('/admin-provider-updated-documents/{id}', [AdminProviderController::class, 'providerDocumentsUpdate'])->name('providerDocumentsUpdate');

    route::get('/admin-provider/role', [AdminProviderController::class, 'fetchRolesName']);

    route::get('/admin-providers-details/{id}', [AdminProviderController::class, 'deleteProviderAccount'])->name('deleteProviderAccount');

    route::post('/admin-providers/regionsFiltering', [AdminProviderController::class, 'filterPhysicianThroughRegions']);
    route::post('/admin-providers-regionsFiltering-mobile', [AdminProviderController::class, 'filterPhysicianThroughRegionsMobileView']);

    route::get('/providers-Locations', [AdminProviderController::class, 'providerLocations'])->name('providerLocation');
    route::get('/providers-map-Locations', [AdminProviderController::class, 'providerMapLocations'])->name('providerMapLocation');

    Route::post('/admin-providers/stopNotification', [AdminProviderController::class, 'stopNotifications'])->name('admin.provider.stop.notification');
    Route::post('/admin-providers/stopNotification/mobile', [AdminProviderController::class, 'stopNotificationsMobileView'])->name('admin.provider.stop.notification.mobile');

    route::post('admin-new-request-support', [AdminController::class, 'sendRequestSupport'])->name('sendRequestSupport');

    route::post('/admin-new-exportNew', [AdminController::class, 'exportNew'])->name('exportNewData');
    route::post('/admin-pending-exportPending', [AdminController::class, 'exportPending'])->name('exportPending');
    route::post('/admin-active-exportActive', [AdminController::class, 'exportActive'])->name('exportActive');
    route::post('/admin-conclude-exportConclude', [AdminController::class, 'exportConclude'])->name('exportConclude');
    route::post('/admin-toclose-exportToClose', [AdminController::class, 'exportToClose'])->name('exportToClose');
    route::post('/admin-new-exportUnPaid', [AdminController::class, 'exportUnpaid'])->name('exportUnPaid');
    route::get('/admin-new-exportAll', [ExcelController::class, 'exportAll'])->name('exportAll');

    route::get('/admin-createRequest', [AdminDashboardController::class, 'createNewRequest'])->name('adminPatientRequest');
    route::post('/admin-createRequest', [AdminDashboardController::class, 'createAdminPatientRequest'])->name('adminCreatedPatientRequest');

    route::get('/admin-new', [AdminController::class, 'fetchRegions']);

    route::post('/dropdown-data', [AdminController::class, 'filterPatientNew'])->name("filterByRegion");
    route::post('/filter-pending', [AdminController::class, 'filterPatientPending'])->name("filterByRegionPending");
    route::post('/filter-active', [AdminController::class, 'filterPatientActive'])->name("filterByRegionActive");
    route::post('/filter-conclude', [AdminController::class, 'filterPatientConclude'])->name("filterByRegionConclude");
    route::post('/filter-toclose', [AdminController::class, 'filterPatientToClose'])->name("filterByRegionToClose");
    route::post('/filter-unpaid', [AdminController::class, 'filterPatientUnpaid'])->name("filterByRegionUnpaid");

    Route::get('/user-access', [AdminController::class, 'UserAccess'])->name('admin.user.access');
    Route::get('/user-access-edit/{id?}', [AdminController::class, 'UserAccessEdit'])->name('admin.user.accessEdit');

    route::get('/adminProfile/my-profile/Edit', [AdminDashboardController::class, 'adminProfilePage'])->name('admin.profile.editing');
    route::get('/admin/profile/{id}', [AdminDashboardController::class, 'adminProfile'])->name('adminProfile');

    route::get('/adminProfile/my-profile/Edit', [AdminDashboardController::class, 'adminProfilePage'])->name('admin.profile.editing');
    route::get('/admin-profile/{id}', [AdminDashboardController::class, 'adminProfile'])->name('adminProfile');

    route::post('/admin-profileEdit/{id}', [AdminDashboardController::class, 'adminProfileEdit'])->name('adminProfileEdit');

    route::post('/admin-adminChangePassword/{id}', [AdminDashboardController::class, 'adminChangePassword'])->name('adminChangePassword');
    route::post('/admin-adminInfoEdit/{id}', [AdminDashboardController::class, 'adminInfoUpdate'])->name('adminInfoUpdate');
    route::post('/admin-adminMailEdit/{id}', [AdminDashboardController::class, 'adminMailInfoUpdate'])->name('adminMailInfoUpdate');

    route::get('/admin-provider-profile/{id}', [AdminDashboardController::class, 'adminEditProviderThroughUserAccess'])->name('adminEditProfileThroughUserAccess');
    route::post('/admin-provider-profile-edited/{id}', [AdminDashboardController::class, 'adminEditedProviderThroughUserAccess'])->name('adminEditedProfileThroughUserAccess');

    route::post('/user-access/filter', [AdminController::class, 'FilterUserAccessAccountTypeWise'])->name('filterUserAccessAccountTypeWise');
    route::post('/user-access-mobile-filter', [AdminController::class, 'FilterUserAccessAccountTypeWiseMobileView'])->name('FilterUserAccessAccountTypeWiseMobileView');

    route::get('/admin-createAdmin', [AdminController::class, 'adminAccount'])->name('createAdminAccount');
    route::post('/admin-adminAccountCreated', [AdminController::class, 'createAdminAccount'])->name('adminAccountCreated');

    route::get('/admin-account-state', [AdminController::class, 'fetchRegionsForState'])->name('fetchStates');
    route::get('/admin-account-role', [AdminController::class, 'fetchRolesForAdminAccountCreate'])->name('fetchRole');

    // Records Page
    Route::get('/search-records', [AdminController::class, 'searchRecordsView'])->name('admin.search.records.view');
    Route::match(['get', 'post'], '/search-records/search', [AdminController::class, 'searchRecordSearching'])->name('admin.search.records');

    route::post('/search-records/export', [AdminController::class, 'downloadFilteredData'])->name('downloadFilteredData');
    Route::get('/search-records/delete/{id}', [AdminController::class, 'deleteSearchRecordData'])->name('admin.search.records.delete');

    Route::get('/sms-logs', [AdminController::class, 'smsRecordsView'])->name('admin.sms.records.view');
    Route::match(['get', 'post'], '/sms-logs/search', [AdminController::class, 'searchSMSLogs'])->name('admin.sms.records.search');

    Route::get('/block-history', [AdminController::class, 'blockHistoryView'])->name('admin.block.history.view');
    Route::post('/block-history/search', [AdminController::class, 'blockHistroySearchData'])->name('admin.block.history.search');
    Route::post('/block-history/update', [AdminController::class, 'updateBlockHistoryIsActive'])->name('admin.block.history.update');
    Route::get('/block-history/unblock/{id}', [AdminController::class, 'unBlockPatientInBlockHistoryPage'])->name('admin.block.history.unblock');
});

Route::middleware('CheckAdminOrProvider')->group(function () {
    // COMMON CODE FOR ADMIN/PROVIDER
    // download document uploaded in view Uploads / Conclude Care
    Route::get('/download/{id}', [CommonOperationController::class, 'download'])->name('download');

    // Delete document
    Route::get('/delete-document/{id?}', [CommonOperationController::class, 'deleteDoc'])->name('document.delete');

    // Operations on ViewUploads page (Download All, Delete All)
    Route::post('/operations', [CommonOperationController::class, 'operations'])->name('operations');

    // Send Mail to patient from listing pages
    Route::post('/send-mail', [CommonOperationController::class, 'sendMailPatient'])->name('send.mail.patient');

    // SEND LINK DASHBOARD PAGE
    // Send Agreement via email and sms, pending page
    Route::post('/send-agreement', [CommonOperationController::class, 'sendAgreementLink'])->name('send.agreement');

    // Dynamically update data of business dropdown based on selection of profession
    Route::get('/fetch-business/{id}', [CommonOperationController::class, 'fetchBusiness'])->name('fetch.business');

    // Dynamically fetch data of business based on selection in dropdown
    Route::get('/fetch-business-data/{id}', [CommonOperationController::class, 'fetchBusinessData'])->name('fetch.business.data');
});


// ---------------- REMOVED FROM SRS -----------------
// Cancel History Page
// These page is removed from SRS
Route::get('/cancel-history', [AdminController::class, 'viewCancelHistory'])->name('admin.cancel.history.view');
Route::post('/cancel-history', [AdminController::class, 'searchCancelCase'])->name('cancel.case.search');

// For Testing Purpose only
Route::get('/test', function () {
    return view('patientSite.agreementDone');
});
