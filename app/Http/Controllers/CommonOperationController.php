<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMailRequest;
use App\Mail\DocsAttachmentMail;
use App\Mail\SendAgreement;
use App\Mail\SendLink;
use App\Mail\SendMailPatient;
use App\Models\Admin;
use App\Models\EmailLog;
use App\Models\HealthProfessional;
use App\Models\Provider;
use App\Models\RequestClient;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\SMSLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use ZipArchive;

class CommonOperationController extends Controller
{
    /**
     * Download any sinlge file function
     *
     * @param int $id id of document/image to be downloaded
     *
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
                $path = public_path() . '/storage/' . $file->file_name;
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
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function operations(Request $request)
    {
        $email = RequestClient::where('request_id', $request->requestId)->first()->email;
        $data = RequestWiseFile::where('request_id', $request->requestId)->get();
        // Delete All Documents or Delete the selected documents
        if ($request->input('operation') === 'delete_all') {
            if (!$request->selected) {
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to Delete!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected');
            }
            RequestWiseFile::whereIn('id', $ids)->delete();

            return redirect()->back();
        }

        if ($request->input('operation') === 'download_all') {
            try {
                // Download All Documents or Download the selected documents
                if (!$request->selected) {
                    if ($data->isEmpty()) {
                        return redirect()->back()->with('noRecordFound', 'There are no records to download!');
                    }
                    $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
                } else {
                    $ids = $request->input('selected');
                }

                $zip = new ZipArchive();
                $zipFile = uniqid() . '-documents.zip';

                if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === true) {
                    foreach ($ids as $id) {
                        $file = RequestWiseFile::where('id', $id)->first();
                        $path = public_path() . '/storage/' . $file->file_name;

                        $zip->addFile($path, $file->file_name);
                    }
                    $zip->close();
                }
                return response()->download(public_path($zipFile))->deleteFileAfterSend(true);
            } catch (\Throwable $th) {
                return view('errors.500');
            }
        }

        if ($request->input('operation') === 'send_mail') {
            // Send Mail of Selected Documents as attachment
            if ($data->isEmpty()) {
                return redirect()->back()->with('noRecordFound', 'There are no records to Send Mail!');
            }

            $request->validate([
                'selected' => 'required',
            ], [
                'selected.required' => 'Please select at least one record.',
            ]);

            $ids = $request->input('selected');

            $zip = new ZipArchive();
            $zipFile = uniqid() . $email . '.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === true) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = public_path() . '/storage/' . $file->file_name;

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            $patient = RequestTable::where('id', $request->requestId)->first();

            try {
                Mail::to($email)->send(new DocsAttachmentMail($email, $zipFile));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            EmailLog::create([
                'role_id' => 1,
                'request_id' => $request->requestId,
                'recipient_name' => $patient->first_name . ' ' . $patient->last_name,
                'confirmation_number' => $patient->confirmation_no,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'create_date' => now(),
                'email_template' => 'mail.blade.php',
                'subject_name' => 'Documets Link Sent',
                'email' => $email,
                'action' => 6,
            ]);

            return redirect()->back()->with('mailDocsSent', 'Mail of all the selected documents is sent!');
        }
    }

    /**
     * Send email to patient.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sendMailPatient(Request $request)
    {
        $request->validate([
            'message' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/'
        ]);
        $requestClient = RequestClient::where('request_id', $request->requestId)->first();
        try {
            $user = Auth::user();
            $provider = Provider::where('user_id', $user->id)->first();

            $providerId = DB::raw('NULL');
            $adminId = DB::raw('NULL');
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
                'recipient_name' => $requestClient->first_name . ' ' . $requestClient->last_name,
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
     * Get userData as per the logged in user
     *
     * @return array return array with roleId and userId
     */
    public function getUserRoleAndId()
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();

        $providerId = null;
        $adminId = null;

        if ($provider) {
            $roleId = 2;
            $providerId = Provider::where('user_id', $user->id)->first()->id;
        } else {
            $roleId = 1;
            $adminId = Admin::where('user_id', $user->id)->first()->id;
        }

        return [
            $roleId,
            $providerId,
            $adminId,
        ];
    }

    /**
     * Provider/Admin Send Agreement Link to Patient from Pending State
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendAgreementLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
        ]);
        $clientData = RequestTable::with('requestClient')->where('id', $request->request_id)->first();

        $roleId = $this->getUserRoleAndId()[0];
        $providerId = $this->getUserRoleAndId()[1];
        $adminId = $this->getUserRoleAndId()[2];

        EmailLog::create([
            'role_id' => $roleId,
            'request_id' => $request->request_id,
            'admin_id' => $adminId,
            'provider_id' => $providerId,
            'recipient_name' => $clientData->requestClient->first_name . ' ' . $clientData->requestClient->last_name,
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
                'sms_template' => 'Hii, Click on the given link to create request',
                'mobile_number' => $request->phone_number,
                'confirmation_number' => $clientData->confirmation_no,
                'recipient_name' => $clientData->requestClient->first_name . ' ' . $clientData->requestClient->last_name,
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
            $sid = config('api.twilio_sid');
            $token = config('api.twilio_auth_token');
            $senderNumber = config('api.sender_number');

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    '+91 99780 71802', // to
                    [
                        'body' => 'Hii ' .  $clientData->requestClient->first_name . ' ' . $clientData->requestClient->last_name . ', Click on the this link to open Agreement:' . url('/patient-agreement/' . $request->request_id),
                        'from' => $senderNumber,
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
     * @param int $id The ID of the selected profession.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBusiness($id)
    {
        $business = HealthProfessional::where('profession', $id)->get();
        return response()->json($business);
    }

    /**
     * Fetches business data based on the provided ID and returns it as a JSON response.
     *
     * @param int $id The ID of the business data to fetch.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBusinessData($id)
    {
        $businessData = HealthProfessional::where('id', $id)->first();
        return response()->json($businessData);
    }

    /**
     * Send Mail and SMS to patient with an link to create request page (from both admin and provider side)
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendMail(SendMailRequest $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submit.request');

        $roleId = $this->getUserRoleAndId()[0];
        $providerId = $this->getUserRoleAndId()[1];
        $adminId = $this->getUserRoleAndId()[2];

        try {
            Mail::to($request->email)->send(new SendLink($request->all()));
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        EmailLog::create([
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'provider_id' => $providerId,
            'is_email_sent' => true,
            'sent_tries' => 1,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
            'recipient_name' => $request->first_name . ' ' . $request->last_name,
            'action' => 1,
        ]);

        try {
            // send SMS
            $sid = config('api.twilio_sid');
            $token = config('api.twilio_auth_token');
            $senderNumber = config('api.sender_number');

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    '+91 99780 71802', // to
                    [
                        'body' => "Hii {$request->first_name} {$request->last_name}, Click on the this link to create request:{$link}",
                        'from' => $senderNumber,
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        SMSLogs::create(
            [
                'role_id' => $roleId,
                'admin_id' => $adminId,
                'provider_id' => $providerId,
                'mobile_number' => $request->phone_number,
                'created_date' => now(),
                'sent_date' => now(),
                'recipient_name' => $request->first_name  . ' ' . $request->last_name,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 1,
                'sms_template' => 'Hii, Click on the below link to create request',
            ]
        );

        return redirect()->back()->with('successMessage', 'Link Sent Successfully!');
    }
}
