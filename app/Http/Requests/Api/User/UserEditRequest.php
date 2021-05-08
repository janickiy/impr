<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'nickname' => 'max:255',
            'birthday' => 'date_format:Y-m-d',
            'gender' => 'integer',
            'contacts' => 'array',
            'settings' => 'array',
        ];
    }
}
