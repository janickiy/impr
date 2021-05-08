<?php

namespace App\Http\Requests\Api\Messages;

use Illuminate\Foundation\Http\FormRequest;

class MessagesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'required',
        ];
    }
}

