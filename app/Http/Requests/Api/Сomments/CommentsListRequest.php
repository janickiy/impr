<?php

namespace App\Http\Requests\Api\Comments;

use Illuminate\Foundation\Http\FormRequest;

class CommentsListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'video_id' =>'required|integer|exists:videos,id',
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
        ];
    }
}
