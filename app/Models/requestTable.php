<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\request_Client;

class RequestTable extends Model
{
    use HasFactory;

    protected $table = 'request';

    protected $primaryKey ='id';

    protected $fillable = [
        'request_type_id',
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
        'email',
        'status',
        'physician_id',
        'confirmation_no',
        'declined_by',
        'is_urgent_email_sent',
        'last_wellness_date',
        'is_mobile',
        'call_type',
        'completed_by_physician',
        'last_reservation_date',
        'accepted_date',
        'relation_name',
        'case_number',
        'case_tag_physician',
        'patient_account_id',
        'created_user_id',
    ];

    public function allusers(){
        return $this->belongsTo(allusers::class);
    }

    // Making relationship with requestClient table

    
    // public function requestClient(){
    //     return $this->belongsTo(request_Client::class);
    // }
}
