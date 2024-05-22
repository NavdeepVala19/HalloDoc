<?php

namespace App\Services;

use App\Mail\SendLink;
use App\Models\EmailLog;
use App\Models\SMSLogs;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class SendLinkToPatientService
{
    public function sendLink($request){
        $firstname = $request->first_name;
        $lastname = $request->last_name;

        // Route name
        $routeName = 'submit.request';

        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route($routeName);

        try {
            Mail::to($request->email)->send(new SendLink($request->all()));
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        try {
            // send SMS
            $sid = getenv('TWILIO_SID');
            $token = getenv('TWILIO_AUTH_TOKEN');
            $senderNumber = getenv('TWILIO_PHONE_NUMBER');

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    '+91 99780 71802', // to
                    [
                        'body' => "Hii {$firstname} {$lastname}, Click on the this link to create request:{$link}",
                        'from' => $senderNumber,
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        EmailLog::create([
            'role_id' => 1,
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

        SMSLogs::create(
            [
                'role_id' => 1,
                'mobile_number' => $request->phone_number,
                'created_date' => now(),
                'sent_date' => now(),
                'recipient_name' => $request->first_name . ' ' . $request->last_name,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 1,
                'sms_template' => 'Hii ,Click on the below link to create request',
            ]
        );

        return true;
    }
}
