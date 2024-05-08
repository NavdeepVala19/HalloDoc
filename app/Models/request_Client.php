<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RequestTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class request_Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    // request_client table is use to create only the patient records
    protected $table = 'request_client';

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

    public function request()
    {
        return $this->belongsTo(RequestTable::class);
    }

    public function request_status()
    {
        return $this->belongs(RequestStatus::class, 'request_id', 'request_id');
    }

    public function request_wise_file()
    {
        return $this->belongsTo(RequestWiseFile::class, 'request_id', 'request_id');
    }

    public function requestClosed()
    {
        return $this->belongsTo(RequestClosed::class, 'request_id', 'request_id');
    }
}
