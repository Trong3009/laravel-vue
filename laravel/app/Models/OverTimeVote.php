<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OverTimeVote extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "over_time_votes";
    protected $fillable = [
        'name',
        'date_ot',
        'object',
        'date_type',
        'shift',
        'start_time',
        'end_time',
        'total_time',
        'multi_time',
        'total_multi',
        'client',
        'description',
        'status',
        'approved_by_user',
        'reason_approval',
        'approved_time',
        'report_ot',
        'user_details_id',
    ];
}
