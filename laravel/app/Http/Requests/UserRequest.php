<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $request = request();
        return [
            'user_name' => ['required', 'min:3', 'max:155',
                Rule::unique('users')->ignore($this->id),
            ],
            'parent_id' => 'nullable|integer',
            'password' => [
                'min:6', 'max:32',
                Rule::requiredIf($request->getMethod() == 'POST'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.unique' => ':attribute đã tồn tại trên hệ thống',
            'user_name.required' => ':attribute không được để trống',
            'user_name.min' => ':attribute không được nhỏ hơn :min ký tự',
            'user_name.max' => ':attribute không được lớn hơn :max ký tự',
            'password.required' => ':attribute không được để trống',
            'password.min' => ':attribute không được nhỏ hơn :min ký tự',
            'password.max' => ':attribute không được lớn hơn :max ký tự',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_name' => 'Tên tài khoản',
            'password' => 'Mật khẩu',
        ];
    }
}
