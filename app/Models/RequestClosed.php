<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestClosed extends Model
{
    use HasFactory;

    protected $table = 'request_closed';

    protected $fillable = [
        'phy_notes',
        'client_notes ',
        'request_id',
        'request_status_id',
    ];


}
