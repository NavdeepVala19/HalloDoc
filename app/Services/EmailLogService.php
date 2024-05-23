<?php

namespace App\Services;

use App\Models\EmailLog;

class EmailLogService
{
    public function createEntry($request, $requestId, $confirmationNumber)
    {
        EmailLog::create([
            'role_id' => 3,
            'request_id' => $requestId,
            'recipient_name' => $request->first_name . ' ' . $request->last_name,
            'confirmation_number' => $confirmationNumber,
            'is_email_sent' => 1,
            'sent_tries' => 1,
            'action' => 5,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => 'Create Account With Provided Email',
            'subject_name' => 'Create account by clicking on below link with below email address',
            'email' => $request->email,
        ]);
    }
}
