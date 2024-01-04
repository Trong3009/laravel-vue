<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_details';
    protected $fillable = [
        'avatar',
        'code',
        'name',
        'email',
        'phone',
        'user_id',
        'birthday',
        'gender',
        'permanent_address',
        'residence_address',
        'domicile',
        'nationality',
        'nation',
        'religion',
        'marital_status',
        'name_father',
        'name_mother',
        'name_wife_husband',
        'birthday_father',
        'birthday_mother',
        'birthday_wife_husband',
        'note_user',
        'person_contact',
        'person_address',
        'person_phone',
        'person_email',
        'vehicle_type',
        'vehicle_name',
        'vehicle_number',
        'type_of_documents',
        'identity_card',
        'date_identity_card',
        'address_identity_card',
        'is_member_communist',
        'number_member_communist',
        'date_communist',
        'address_communist',
        'health_condition',
        'height',
        'weight',
        'note_health_condition',
        'bank_number',
        'bank_name',
        'bank_branch',
        'bank_account',
        'transfer_level',
        'training_units',
        'specialize',
        'probation_day',
        'official_day',
        'position',
        'job_title',
        'work_note',
        'work_status',
        'quit_date',
        'quit_reason',
        'basic_salary',
        'responsibility_salary',
        'meal_allowance',
        'travel_allowance',
        'insurance_salary',
        'insurance_amount',
    ];
}
