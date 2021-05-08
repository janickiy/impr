<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use App\Models\Video;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;

class SavePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'hashtag' => 'required|string|max:255|regex:/^[a-zA-Z\d]+/i',
            'is_commentable' => 'required|boolean',
            'gender' => [
                'required',
                Rule::in([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_UNISEX]),
            ],
            'is_paid' => 'required|boolean',
            'amount' => [
                'regex:/^\d+(\.\d{1,2})?$/',
                Rule::requiredIf((bool) $this->is_paid),
            ],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => 'required|integer|exists:tags,id|distinct',
            'covers' => 'array|max:4|nullable',
            'covers.*' => [
                'integer',
                Rule::exists('images', 'id')
                    ->where('user_id', optional(request()->user())->id),
            ],
            'video_id' => [
                'required',
                Rule::exists('videos', 'id')->where(function (Builder $q) {
                    return $q->where('user_id', optional(request()->user())->id)
                        ->where('modification', Video::MODIFICATION_ORIGINAL);
                }),
            ],
        ];
    }
}
