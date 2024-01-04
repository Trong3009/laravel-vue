<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LateVoteRequest extends FormRequest
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
            'late_date' => ['required','date','before:today'],
            'start_time' => 'timezone',
            'end_time' => 'timezone',
            'reason' => 'required|max:180',
        ];
    }
}
