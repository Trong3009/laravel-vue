<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required', 'min:5', 'max:30',
                Rule::unique('user_details')->ignore($this->id),
            ],
            'name' => 'required|max:150|',
            'phone' => 'required|numeric|digits_between:10,11',
            'birthday' => ['required', 'date', 'before:today'],
            'email' => [
                'required', 'max:150', 'email',
                Rule::unique('user_details')->ignore($this->id),
            ],
            'gender' => 'max:1',
            'permanent_address' => 'max:255',
            'residence_address' => 'max:255',
            'domicile' => 'max:255',
            'nationality' => 'max:150',
            'nation' => 'max:50',
            'religion' => 'max:50',
            'marital_status' => 'max:1',
            'name_father' => 'max:150',
            'name_mother' => 'max:150',
            'name_wife_husband' => 'max:150',
            'birthday_father' => 'max:15',
            'birthday_mother' => 'max:15',
            'birthday_wife_husband' => 'max:15',
            'note_user' => 'max:255',
            'person_contact' => 'max:150',
            'person_address' => 'max:255',
            'person_phone' => 'nullable|numeric|digits_between:10,11',
            'person_email' => ['nullable', 'max:150', 'email'],
            'vehicle_type' => 'max:150',
            'vehicle_name' => 'max:150',
            'vehicle_number' => 'max:50',
            'type_of_documents' => 'max:1',
            'date_identity_card' => 'max:15',
            'address_identity_card' => 'max:255',
            'is_member_communist' => 'max:1',
            'number_member_communist' => 'max:30',
            'date_communist' => 'max:15',
            'address_communist' => 'max:255',
            'health_condition' => 'max:255',
            'height' => 'max:15',
            'weight' => 'max:15',
            'note_health_condition' => 'max:255',
            'bank_number' => 'max:20',
            'bank_name' => 'max:255',
            'bank_branch' => 'max:255',
            'bank_account' => 'max:150',
            'transfer_level' => 'max:150',
            'training_units' => 'max:255',
            'specialize' => 'max:255',
            'probation_day' => 'max:15',
            'official_day' => 'max:15',
            'position' => 'max:150',
            'job_title' => 'max:150',
            'work_note' => 'max:255',
            'work_status' => 'max:1',
            'quit_date' => 'max:15',
            'quit_reason' => 'max:255',
            'basic_salary' => 'max:20',
            'responsibility_salary' => 'max:20',
            'meal_allowance' => 'max:20',
            'travel_allowance' => 'max:20',
            'insurance_salary' => 'max:20',
            'insurance_amount' => 'max:20',
        ];
    }

    public function attributes(): array
    {
        return [
            //
        ];
    }
}
