<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Onsite extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onsites';
    protected $fillable = [
        'name',
        'date_onsite',
        'start_time',
        'end_time',
        'file_onsite',
        'location',
        'object',
        'approved_by',
        'status',
        'description',
        'file_image_onsite',
    ];
}
