<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\EmailLog;

class CreateEmailLogService
{
    /**
     * enter log after send email in email logs
     *
     * @param mixed $request
     * @param mixed $requestId
     * @param mixed $providerId
     * @param mixed $adminId
     *
     * @return void
     */
    public function storeEmailLogs($request, $requestId, $providerId = null, $adminId = null)
    {
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $roleId = $providerId ?? ($adminId ? 1 : 3);

        EmailLog::create([
            'role_id' => $roleId,
            'request_id' => $requestId,
            'confirmation_number' => $confirmationNumber,
            'is_email_sent' => 1,
            'recipient_name' => $request->first_name . ' ' . $request->last_name,
            'sent_tries' => 1,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => $request->email,
            'subject_name' => 'Create account by clicking on below link with below email address',
            'email' => $request->email,
            'action' => 5,
            'provider_id' => $providerId,
            'admin_id' => $adminId,
        ]);
    }
}
