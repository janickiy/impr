<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required',
            'new_password' => 'required|string|min:8|max:32|different:password',
            'password_again' => 'required|string|min:8|max:32|same:new_password',
        ];
    }
}
