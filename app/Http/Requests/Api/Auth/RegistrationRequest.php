<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
            'firstname' => 'required',
            'lastname' => 'required',
            'nickname' => 'required',
            'birthday' => 'date_format:Y-m-d',
            'contacts' => 'array',
            'settings' => 'array',
        ];
    }
}
