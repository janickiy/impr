<?php

namespace App\Http\Requests\Api\Comments;

use Illuminate\Foundation\Http\FormRequest;

class CommentsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'video_id' => 'nullable|integer|exists:videos,id',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'comment' => 'required',
        ];
    }
}
