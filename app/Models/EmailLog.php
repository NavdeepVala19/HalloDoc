<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_log';

    protected $guarded = [];

    public function roles()
    {
        return $this->hasOne(Roles::class, 'id', 'role_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestTable::class, 'request_id', 'id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
