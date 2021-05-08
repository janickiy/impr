<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string|exists:password_resets,token',
            'password' => 'required|string|min:8|confirmed|max:32|regex:/^[\w]+$/',
        ];
    }
}
