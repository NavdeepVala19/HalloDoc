<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSLogs extends Model
{
    use HasFactory;

    protected $table = 'sms_log'; 


    public function provider(){
        return $this->belongsTo(Provider::class);
    }

}
