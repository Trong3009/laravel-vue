<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PauseVoteRequest extends FormRequest
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
            'reason' => 'required|max:180',
            'since_session' => 'max:180',
            'todate_session' => 'max:180',
            'number_days' => 'max:100',
            'types_break' => 'max:120',
            'since_date' => ['required', 'date', 'before:today'],
            'todate_date' => 'max:180',
        ];
    }
}
