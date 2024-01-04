<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PauseVote extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pause_votes";
    
    protected $fillable = [
        'name',
        'since_session',
        'todate_session',
        'number_days',
        'reason',
        'status',
        'types_break',
        'salary_percentege',
        'history_time',
        'reason_approved',
        'approved_by',
        'since_date',
        'todate_date',
        'users_detail_id',
    ];
}
