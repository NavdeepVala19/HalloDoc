<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseTag extends Model
{
    // For showing select options on cancel case by admin(Cost Issue ,Inappropriate for service, Provider not available, Location problem)
    // The value selected for cancellation will be stored in requestTable
    use HasFactory;

    protected $table = 'case_tag';

    protected $guarded = [];
}
