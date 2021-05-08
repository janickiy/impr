<?php

namespace App\Http\Controllers\Api\Media;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Media\UploadVideoCoverRequest;

/**
 * @group Медиа (media).
 */
class ImageController extends Controller
{
    /**
     * Загрузка обложки видео (upload video cover).
     *
     * @authenticated
     *
     * @param UploadVideoCoverRequest $request
     *
     * @return JsonResponse
     */
    public function uploadVideoCover(
        UploadVideoCoverRequest $request
    ): JsonResponse {
        $image = Image::create([
            'filename' => $request->file('image')->store('', 's3_hot'),
            'user_id' => $request->user()->id,
        ]);

        return response()->json($image);
    }
}
