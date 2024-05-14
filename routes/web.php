<?php

use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminProviderController;
use App\Http\Controllers\CommonOperationController;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

// All Exprt Data to Excel Functionality -> controllers
use App\Http\Controllers\ExcelController;

// All Patient Related functionality -> controllers
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientLoginController;
use App\Http\Controllers\FamilyRequestController;
use App\Http\Controllers\PatientAccountController;
use App\Http\Controllers\PatientProfileController;
use App\Http\Controllers\BusinessRequestController;
use App\Http\Controllers\ConciergeRequestController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\PatientViewDocumentsController;
use App\Http\Controllers\ProviderActionController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProviderSchedulingController;
use App\Http\Controllers\SchedulingController;

// ******************************* SHIVESH **********************************************

//* First page of patient site
route::get('/', [Controller::class, 'patientSite'])->name('patient.home_page');


//* Types of request
route::get('/submit-requests', [Controller::class, 'submitScreen'])->name('submitRequest');

//* Patient request create
route::get('/submit-requests/patient', [PatientController::class, 'patientRequests'])->name('patient.request.submit.view');
Route::post('/patient-created', [PatientController::class, 'create'])->name('patient.request.submit');

//* Family request creating 
route::get('/submit-requests/family', [FamilyRequestController::class, 'familyRequests'])->name('family.request.submit.view');
Route::post('/family-created', [FamilyRequestController::class, 'create'])->name('family.request.submit');

//* Concierge request creating
route::get('/submit-requests/concierge', [ConciergeRequestController::class, 'conciergeRequests'])->name('concierge.request.submit.view');
Route::post('/concierge-created', [ConciergeRequestController::class, 'create'])->name('concierge.request.submit');

//* Business request creating
route::get('/submit-requests/business', [BusinessRequestController::class, 'businessRequests'])->name('business.request.submit.view');
Route::post('/business-created', [BusinessRequestController::class, 'create'])->name('business.request.submit');

//* Create account of patient
route::get('/patient-register', [PatientAccountController::class, 'patientRegister'])->name('patient.register.view');
route::post('/patient-registered', [PatientAccountController::class, 'createAccount'])->name('patient.registered');


//* Patient login page
route::get('/patient/login', [PatientLoginController::class, 'loginScreen'])->name('patient.login.view');
route::post('/patient/logged-in', [PatientLoginController::class, 'userLogin'])->name('patient.login');

//* Reset password of patient 
route::get('/patient/forgot-password', [PatientLoginController::class, 'resetpassword'])->name('patient.forgot.password');
route::post('/patient/forgot-password-link', [PatientLoginController::class, 'submitForgetPasswordForm'])->name('forgot.password');

Route::get('patient/reset-password/{token}', [PatientLoginController::class, 'showResetPasswordForm'])->name('patient.reset.password.view');
Route::post('patient/update-password', [PatientLoginController::class, 'submitResetPasswordForm'])->name('patient.update.password');


route::middleware('CheckPatientLogin')->group(function () {

    //* Patient logout
    route::get('/patient/logout', [PatientLoginController::class, 'logout'])->name('patient.logout');

    //* Patient Dashboard 
    route::get('/patient/dashboard', [PatientDashboardController::class, 'patientDashboard'])->name('patient.dashboard');

    //* Edit profile of patient 
    route::get('/patient/profile', [PatientProfileController::class, 'patientEdit'])->name('patient.profile.view');
    route::get('/patient/profile-edit/{id}', [PatientProfileController::class, 'patientprofileEdit'])->name('patient.profile.edit.view');
    route::post('/patient/profile-updated', [PatientProfileController::class, 'patientUpdate'])->name('patient.profile.edited');
    route::get('/patient/map-location', [PatientProfileController::class, 'patientMapLocation'])->name('patient.location.on.map');

    //* Create New Request or Someone else request from Patient Dashboard
    route::get('/patient/submit-requests', [PatientDashboardController::class, 'createNewRequest'])->name('patient.submit.new.request');
    route::post('/patient/submitted-patient-requests', [PatientDashboardController::class, 'createNewPatient'])->name('patient.new.request.submitted');

    route::get('/patient/submit-someone-requests', [PatientDashboardController::class, 'createSomeoneRequest'])->name('submit.someone.request');
    route::post('/patient/submitted-someone-requests', [PatientDashboardController::class, 'createSomeOneElseRequest'])->name('request.someone.submitted');

    //* View Documents
    route::get('/patient/view-documents/{id}', [PatientViewDocumentsController::class, 'patientViewDocument'])->name('patient.documents.view');
    route::post('/patient/upload-documents', [PatientViewDocumentsController::class, 'uploadDocs'])->name('patient.upload.document');
    route::get('/patient/single-downloads/{id}', [PatientViewDocumentsController::class, 'downloadOne'])->name('patient.download.one.document');
    route::post('/patient/multiple-downloads', [PatientViewDocumentsController::class, 'downloadSelectedFiles'])->name('patient.download.multiple.files');
});
//  *******************************************************************************************************



//  ***************************************************************************************************************************************
// it will show agreement page
route::get('/patientAgreement/{data}', [PatientDashboardController::class, 'viewAgreement'])->name('patientAgreement');
// Agreement Agreed by patient
Route::post('/agree-agreement', [PatientDashboardController::class, 'agreeAgreement'])->name('patient.agree.agreement');
// Agreement Cancelled by patient
Route::post('/cancel-agreement', [PatientDashboardController::class, 'cancelAgreement'])->name('patient.cancel.agreement');
//  ***************************************************************************************************************************************

// **********************************************************ADMIN***************************************************************

//* admin/provider LogIn
route::get('/login', [AdminLoginController::class, 'adminLogin'])->name('login');
route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');

route::post('/admin-logged-in', [AdminLoginController::class, 'userLogin'])->name('admin.login');

//* admin/provider ResetPassword
route::get('/reset-password', [AdminLoginController::class, 'adminResetPassword'])->name('admin.reset.password.view');
route::post('/reset-password-link', [AdminLoginController::class, 'submitForgetPasswordForm'])->name('admin.forgot.password');

//* admin/provider Update Password
Route::get('/update-password/{token}', [AdminLoginController::class, 'showUpdatePasswordForm'])->name('admin.update.password.view');
Route::post('/updated-password', [AdminLoginController::class, 'submitUpdatePasswordForm'])->name('admin.password.updated');

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
    route::get('/admin-providers', [AdminProviderController::class, 'readProvidersInfo'])->name('admin.providers.list');

    route::post('/admin-send-msg-provider/{id}', [AdminProviderController::class, 'sendMailToContactProvider'])->name('send.msg.to.provider');

    route::get('/admin-new-provider', [AdminProviderController::class, 'newProvider'])->name('admin.create.new.provider');
    route::post('/admin-create-new-provider', [AdminProviderController::class, 'adminCreateNewProvider'])->name('admin.created.provider');

    route::get('/admin-edit-provider/{id}', [AdminProviderController::class, 'editProvider'])->name('admin.edit.providers');

    route::post('/admin-provider-updated-accounts/{id}', [AdminProviderController::class, 'updateProviderAccountInfo'])->name('update.account.info.providers');
    route::post('/admin-provider-updated-infos/{id}', [AdminProviderController::class, 'providerInfoUpdate'])->name('update.info.providers');
    route::post('/admin-provider-updated-mail-infos/{id}', [AdminProviderController::class, 'providerMailInfoUpdate'])->name('update.mailing.providers');
    route::post('/admin-provider-updated-profile-data/{id}', [AdminProviderController::class, 'providerProfileUpdate'])->name('update.profile.providers');
    route::post('/admin-provider-updated-documents/{id}', [AdminProviderController::class, 'providerDocumentsUpdate'])->name('update.documents.providers');

    route::get('/admin-provider/role', [AdminProviderController::class, 'fetchRolesName']);

    route::get('/admin-providers-delete-details/{id}', [AdminProviderController::class, 'deleteProviderAccount'])->name('delete.provider_account');

    route::post('/admin-providers/regionsFiltering', [AdminProviderController::class, 'filterPhysicianThroughRegions']);
    route::post('/admin-providers-regionsFiltering-mobile', [AdminProviderController::class, 'filterPhysicianThroughRegionsMobileView']);

    route::get('/providers-locations', [AdminProviderController::class, 'providerLocations'])->name('provider.location');
    route::get('/providers-map-Locations', [AdminProviderController::class, 'providerMapLocations'])->name('providerMapLocation');

    Route::post('/admin-providers/stopNotification', [AdminProviderController::class, 'stopNotifications'])->name('admin.provider.stop.notification');
    Route::post('/admin-providers/stopNotification/mobile', [AdminProviderController::class, 'stopNotificationsMobileView'])->name('admin.provider.stop.notification.mobile');

    route::post('admin-new-request-support', [AdminController::class, 'sendRequestSupport'])->name('send.request_support');

    route::post('/admin-new-exportNew', [AdminController::class, 'exportNew'])->name('export.new_data');
    route::post('/admin-pending-exportPending', [AdminController::class, 'exportPending'])->name('export.pending_data');
    route::post('/admin-active-exportActive', [AdminController::class, 'exportActive'])->name('export.active_data');
    route::post('/admin-conclude-exportConclude', [AdminController::class, 'exportConclude'])->name('export.conclude_data');
    route::post('/admin-toclose-exportToClose', [AdminController::class, 'exportToClose'])->name('export.toclose_data');
    route::post('/admin-unpaid-exportUnPaid', [AdminController::class, 'exportUnpaid'])->name('export.unpaid_data');
    route::get('/admin-new-exportAll', [ExcelController::class, 'exportAll'])->name('export.all_data');

    route::get('/admin-submit-requests', [AdminDashboardController::class, 'createNewRequest'])->name('submit.patient.request.view');
    route::post('/admin-submitted-requests', [AdminDashboardController::class, 'createAdminPatientRequest'])->name('admin.submit.patient.request');

    route::get('/admin-new', [AdminController::class, 'fetchRegions']);
    route::post('/filter-new', [AdminController::class, 'filterPatient'])->name("filter.region_new");

    Route::get('/user-access', [AdminController::class, 'UserAccess'])->name('admin.user.access');
    Route::get('/user-access-edit/{id?}', [AdminController::class, 'UserAccessEdit'])->name('admin.user.accessEdit');
    route::post('/user-access/filter', [AdminController::class, 'FilterUserAccessAccountTypeWise'])->name('filter.user.access.account_wise');
    route::post('/user-access-mobile-filter', [AdminController::class, 'FilterUserAccessAccountTypeWiseMobileView'])->name('filter.user.access.account_wise_mobile');

    route::get('/admin-profile-edit', [AdminDashboardController::class, 'adminProfilePage'])->name('admin.profile.editing');
    route::get('/admin-profile-update/{id}', [AdminDashboardController::class, 'adminProfile'])->name('edit.admin.profile');

    route::post('/admin-update-password/{id}', [AdminDashboardController::class, 'adminChangePassword'])->name('admin.password.update');
    route::post('/admin-info-updates/{id}', [AdminDashboardController::class, 'adminInfoUpdate'])->name('admin.info.update');
    route::post('/admin-mail-updates/{id}', [AdminDashboardController::class, 'adminMailInfoUpdate'])->name('admin.mail.info.update');

    route::get('/admin-create-new-admin', [AdminController::class, 'adminAccount'])->name('create.new.admin.view');
    route::post('/admin-new-account-created', [AdminController::class, 'createAdminAccount'])->name('new.admin.created');

    route::get('/admin-account-state', [AdminController::class, 'fetchRegionsForState'])->name('fetch.state');
    route::get('/admin-account-role', [AdminController::class, 'fetchRolesForAdminAccountCreate'])->name('fetch.role');

    // Records Page
    Route::get('/search-records', [AdminController::class, 'searchRecordsView'])->name('admin.search.records.view');
    Route::match(['get', 'post'], '/search-records/search', [AdminController::class, 'searchRecordSearching'])->name('admin.search.records');

    route::post('/search-records/export', [AdminController::class, 'downloadFilteredData'])->name('export.search.records.filtered_data');
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
