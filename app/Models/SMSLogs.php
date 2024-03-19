<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSLogs extends Model
{
    use HasFactory;

    protected $table = 'sms_log';


    protected $fillable = [
        'recipient_name',
        'sms_template',
        'mobile_number',
        'confirmation_number',
        'created_date',
        'sent_date',
        'is_sms_sent',
        'sent_tries',
        'action',
    ];


    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
