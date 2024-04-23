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
            // Generate the file path
            $path = (public_path() . '/storage/' . $file->file_name);

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
            $zipFile = $email . '.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = (public_path() . '/storage/' . $file->file_name);

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            EmailLog::create([
                'role_id' => 1,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'email_template' => 'mail.blade.php',
                'subject_name' => 'Documets Link Sent',
                'email' => $email,
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

        return redirect()->back()->with('agreementSent', 'Agreement sent to patient successfully!');
    }

    // Common Code for Admin/Provider
    // Fetch business values (health_professional values) as per the profession selected in Send Orders page
    public function fetchBusiness(Request $request, $id)
    {
        $business = HealthProfessional::where('profession', $id)->get();
        return response()->json($business);
    }
    // Ajax call for fetching business data and showing in the page
    public function fetchBusinessData($id)
    {
        $businessData = HealthProfessional::where('id', $id)->first();
        return response()->json($businessData);
    }
}
