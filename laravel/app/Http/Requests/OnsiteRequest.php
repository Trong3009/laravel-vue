<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnsiteRequest extends FormRequest
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
            'location' => 'required|max:120',
            'date_ot' => 'date',
            'object' => 'max:120',
            'start_time' => 'timezone',
            'end_time' => 'timezone',
            'total_time' => 'integer',
            
        ];
    }
}
