<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoParams implements Rule
{
    private const MAX_WIDTH = 1080;

    private const MAX_HEIGHT = 1920;

    private const MAX_DURATION_IN_SECONDS = 300;

    private const MIN_DURATION_IN_SECONDS = 15;

    private const ASPECT_RATIO = ['0:1', '9:16'];

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $video = FFMpeg::fromDisk('root')
            ->open($value->getRealPath())
            ->getVideoStream();

        return optional($video)->get('width') <= static::MAX_WIDTH
            && optional($video)->get('height') <= static::MAX_HEIGHT
            && optional($video)->get('duration') <= static::MAX_DURATION_IN_SECONDS
            && optional($video)->get('duration') >= static::MIN_DURATION_IN_SECONDS
            && in_array(optional($video)->get('display_aspect_ratio'), static::ASPECT_RATIO);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans(
            'validation.video_params',
            [
                'dimension' => static::MAX_WIDTH.'x'.static::MAX_HEIGHT,
                'max_duration' => static::MAX_DURATION_IN_SECONDS,
                'min_duration' => static::MIN_DURATION_IN_SECONDS,
                'aspect_ratio' => implode(' or ', static::ASPECT_RATIO),
            ]
        );
    }
}
