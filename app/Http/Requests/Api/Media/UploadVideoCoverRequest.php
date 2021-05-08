<?php

namespace App\Http\Requests\Api\Media;

use Illuminate\Foundation\Http\FormRequest;

class UploadVideoCoverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'max:2000',
                'mimes:png,jpg',
                'dimensions:max_width=1080,max_height=1920',
            ],
        ];
    }
}
