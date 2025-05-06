<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'max:255',
                'regex:/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?\']+$/',
                'unique:users,name,' . Auth::id(),
            ],
            'email' => [
                'string',
                'email',
                'unique:users,email' . Auth::id(),
            ],
            'oldPassword' => [
                'string',
                'min:8',
                'max:15'
            ],
            'newPassword' => [
                'string',
                'min:8',
                'max:15'
            ],
            'role' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Here, we assume that the current user password should match.
            if (!Hash::check($this->input('oldPassword'), Auth::user()->password)) {
                $validator->errors()->add('oldPassword', __('The provided old password does not match our records.'));
            }
        });
    }
}
