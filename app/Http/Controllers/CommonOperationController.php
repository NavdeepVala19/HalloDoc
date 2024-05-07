<?php

namespace App\Http\Controllers;

// For Sending SMS & Email
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;
use App\Models\SMSLogs;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Mail\SendAgreement;
use App\Models\RequestTable;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use App\Mail\DocsAttachmentMail;
use App\Mail\SendMailPatient;
use App\Models\HealthProfessional;

class CommonOperationController extends Controller
{
    /**
     * Download any sinlge file function
     *
     * @param int $id id of document/image to be downloaded 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse 
     */
    public function download($id = null)
    {
        try {
            // Retrieve the file details from the database
            $file = RequestWiseFile::where('id', $id)->first();
            if ($file->is_finalize) {
                $path = storage_path('app/encounterForm/' . $file->file_name);
            } else {
                // Generate the file path
                $path = (public_path() . '/storage/' . $file->file_name);
            }

            return response()->download($path);
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * Delete a single document from viewUploads page
     *
     * @param int $id id of document/image to be deleted 
     */
    public function deleteDoc($id = null)
    {
        RequestWiseFile::where('id', $id)->delete();

        return redirect()->back();
    }


    /**
     * Perform different operations as per the operation selected (Delete All, Download All, Send Mail)
     *
     * @param Request $request It will have different operations
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function operations(Request $request)
    {
        $email = request_Client::where('request_id', $request->requestId)->first()->email;
        // Delete All Documents or Delete the selected documents
        if ($request->input('operation') == 'delete_all') {
            if (empty($request->input('selected'))) {
                $data = RequestWiseFile::where('request_id', $request->requestId)->get();
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to Delete!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected');
            }
            RequestWiseFile::whereIn('id', $ids)->delete();

            return redirect()->back();
        } else if ($request->input('operation') == 'download_all') {
            // Download All Documents or Download the selected documents
            if (empty($request->input('selected'))) {
                $data = RequestWiseFile::where('request_id', $request->requestId)->get();
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to download!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected');
            }

            $zip = new ZipArchive;
            $zipFile = uniqid() . "-" . 'documents.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = (public_path() . '/storage/' . $file->file_name);

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            return response()->download(public_path($zipFile))->deleteFileAfterSend(true);
        } else if ($request->input('operation') == 'send_mail') {
            // Send Mail of Selected Documents as attachment
            $data = RequestWiseFile::where('request_id', $request->requestId)->get();
            if ($data->isEmpty()) {
                return redirect()->back()->with('noRecordFound', 'There are no records to Send Mail!');
            }

            $request->validate([
                'selected' => 'required'
            ], [
                'selected.required' => 'Please select at least one record.'
            ]);

            $ids = $request->input('selected');

            $zip = new ZipArchive;
            $zipFile =  uniqid() . $email . '.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = (public_path() . '/storage/' . $file->file_name);

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            $patient = RequestTable::where('id', $request->requestId)->first();

            EmailLog::create([
                'role_id' => 1,
                'request_id' => $request->requestId,
                'recipient_name' => $patient->first_name . " " . $patient->last_name,
                'confirmation_number' => $patient->confirmation_no,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'create_date' => now(),
                'email_template' => 'mail.blade.php',
                'subject_name' => 'Documets Link Sent',
                'email' => $email,
                'action' => 6
            ]);

            try {
                Mail::to($email)->send(new DocsAttachmentMail($email, $zipFile));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            return redirect()->back()->with('mailDocsSent', 'Mail of all the selected documents is sent!');
        }
    }

    /**
     * Send email to patient.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sendMailPatient(Request $request)
    {
        $requestClient = request_Client::where('request_id', $request->requestId)->first();
        try {

            $user = Auth::user();
            $provider = Provider::where('user_id', $user->id)->first();

            $providerId = DB::raw("NULL");
            $adminId = DB::raw("NULL");
            $message = $request->message;

            if ($provider) {
                $roleId = 2;
                $providerId = Provider::where('user_id', $user->id)->first()->id;
                $provider = Provider::where('user_id', $user->id)->first();
                Mail::to($requestClient->email)->send(new SendMailPatient($requestClient, $provider, $message));
            } else {
                $roleId = 1;
                $adminId = Admin::where('user_id', $user->id)->first()->id;
                $admin = Admin::where('user_id', $user->id)->first();
                Mail::to($requestClient->email)->send(new SendMailPatient($requestClient, $admin, $message));
            }

            EmailLog::create([
                'role_id' => $roleId,
                'request_id' => $request->request_id,
                'admin_id' => $adminId,
                'provider_id' => $providerId,
                'recipient_name' => $requestClient->first_name . " " . $requestClient->last_name,
                'email_template' => 'sendMailPatient.blade.php',
                'subject_name' => 'Send Mail to patient',
                'email' => $requestClient->email,
                'confirmation_number' => $requestClient->request->confirmation_no,
                'create_date' => now(),
                'sent_date' => now(),
                'is_email_sent' => 1,
                'sent_tries' => 1,
                'action' => 6,
            ]);

            return redirect()->back()->with('successMessage', 'Mail sent to patient successfully!');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * Provider/Admin Send Agreement Link to Patient from Pending State
     *
     * @param Request $request 
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendAgreementLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required'
        ]);
        $clientData = RequestTable::with('requestClient')->where('id', $request->request_id)->first();

        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();

        $providerId = DB::raw("NULL");
        $adminId = DB::raw("NULL");

        if ($provider) {
            $roleId = 2;
            $providerId = Provider::where('user_id', $user->id)->first()->id;
        } else {
            $roleId = 1;
            $adminId = Admin::where('user_id', $user->id)->first()->id;
        }

        $id = $request->request_id;
        EmailLog::create([
            'role_id' => $roleId,
            'request_id' => $request->request_id,
            'admin_id' => $adminId,
            'provider_id' => $providerId,
            'recipient_name' => $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name,
            'email_template' => 'sendAgreementLink.blade.php',
            'subject_name' => 'Agreement Link Sent to Patient',
            'email' => $request->email,
            'confirmation_number' => $clientData->confirmation_no,
            'create_date' => now(),
            'sent_date' => now(),
            'is_email_sent' => 1,
            'sent_tries' => 1,
            'action' => 4,
        ]);

        SMSLogs::create(
            [
                'sms_template' => "Hii, Click on the given link to create request",
                'mobile_number' => $request->phone_number,
                'confirmation_number' => $clientData->confirmation_no,
                'recipient_name' => $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name,
                'role_id' => $roleId,
                'admin_id' => $adminId,
                'request_id' => $request->request_id,
                'provider_id' => $providerId,
                'created_date' => now(),
                'sent_date' => now(),
                'is_sms_sent' => 1,
                'sent_tries' => 1,
                'action' => 4,
            ]
        );

        try {
            Mail::to($request->email)->send(new SendAgreement($clientData));
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        try {
            // send SMS 
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $senderNumber = getenv("TWILIO_PHONE_NUMBER");

            $twilio = new Client($sid, $token);

            $message = $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "Hii " .  $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name . ", Click on the this link to open Agreement:" . url('/patientAgreement/' . $id),
                        "from" =>  $senderNumber
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        return redirect()->back()->with('successMessage', 'Agreement sent to patient successfully!');
    }

    // Common Code for Admin/Provider
    /**
     * Fetch business values (health_professional values) based on the profession selected.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param int $id The ID of the selected profession.
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBusiness(Request $request, $id)
    {
        $business = HealthProfessional::where('profession', $id)->get();
        return response()->json($business);
    }

    /**
     * Fetches business data based on the provided ID and returns it as a JSON response.
     *
     * @param int $id The ID of the business data to fetch.
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBusinessData($id)
    {
        $businessData = HealthProfessional::where('id', $id)->first();
        return response()->json($businessData);
    }
}
