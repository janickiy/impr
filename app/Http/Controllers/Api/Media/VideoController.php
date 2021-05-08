<?php

namespace App\Http\Controllers\Api\Media;

use App\Jobs\ProcessVideo;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Media\UploadVideoRequest;

/**
 * @group Медиа (media).
 */
class VideoController extends Controller
{
    /**
     * Загрузка видео (upload video).
     *
     * @authenticated
     *
     * @param UploadVideoRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(UploadVideoRequest $request): JsonResponse
    {
        $this->dispatch(new ProcessVideo($request->file('video'), $request->user()));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
