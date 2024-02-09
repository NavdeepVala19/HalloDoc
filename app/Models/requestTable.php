<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\requestClient;

class requestTable extends Model
{
    use HasFactory;

    protected $table = 'request';

    public function requestClient()
    {
        return $this->belongsTo(requestClient::class, 'id', 'request_id');
    }
}
