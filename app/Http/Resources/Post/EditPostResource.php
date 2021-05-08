<?php

namespace App\Http\Resources\Post;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Video $compressedVideo */
        $compressedVideo = $this->resource->video->compressed();
        $covers = $this->resource->covers->isEmpty()
            ? $this->resource->video->covers
            : $this->resource->covers;

        return array_merge(
            $this->resource->makeHidden(['video'])->toArray(),
            [
                'video_url' => $compressedVideo->url,
                'covers' => $covers,
            ]
        );
    }
}
