<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowPostResource extends JsonResource
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
        return [
            'video_url' => $this->resource->video->compressed()->url, // TODO: логика платных видео
            'user' => [
                'id' => $this->resource->user->id,
                'login' => $this->resource->user->login,
            ],
            'title' => $this->resource->title,
            'hashtag' => $this->resource->hashtag,
            'shows' => $this->resource->shows,
        ];
    }
}
