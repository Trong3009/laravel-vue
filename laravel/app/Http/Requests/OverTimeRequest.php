<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OverTimeRequest extends FormRequest
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
            'object' => 'required|max:180',
            'date_ot' => ['date','before:today'],
            'date_type' => 'max:120',
            'shift' => 'max:120',
            'start_time' => 'timezone',
            'end_time' => 'timezone',
        ];
    }
}
