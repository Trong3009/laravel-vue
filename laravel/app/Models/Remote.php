<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "remotes";
    protected $fillable = [
        'name',
        'date_remote',
        'start_time',
        'end_time',
        'file_remote',
        'approved_by',
        'status',
        'description',
        'file_image_remote'
    ];
}
