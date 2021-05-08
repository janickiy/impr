<?php

namespace App\Http\Requests\Api\Media;

use App\Rules\VideoParams;
use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'video' => [
                'required',
                'file',
                'max:400000',
                'mimes:mp4,mov',
                new VideoParams,
            ],
        ];
    }
}
