<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LateVote extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'late_votes';
    protected $fillable = [
        'name',
        'late_date',
        'shift_job',
        'start_time',
        'end_time',
        'number_minute',
        'object',
        'reason',
        'status',
        'approved_by',
        'reason_approved',
        'time_approved',
        'user_details_id',
    ];
}
