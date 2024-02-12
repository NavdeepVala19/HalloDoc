<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class request_Client extends Model
{
    use HasFactory;

    protected $table ='request_client';

    protected $fillable = [
        'request_id',
        'notes',
        'first_name',
        'last_name',
        'phone_number',
        'location',
        'address',
        'noti_mobile',
        'noti_email',
        'email',
        'str_month',
        'int_year',
        'int_date',
        'is_mobile',
        'street',
        'city',
        'state',
        'zipcode',
        'CommunicationType',
        'RemindReservationCount',
        'RemindHouseCallCount',
        'IsSetFollowupSent',
        'IsReservationReminderSent',
        'Latitude',
        'Longitude',
    ];

    // public function request(){
    //     return $this->belongsTo(request::class);
    // }
}
