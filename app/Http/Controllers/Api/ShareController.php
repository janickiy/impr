<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\ShareRequest;
use App\Models\Share;

class ShareController extends BaseController
{
    public function __invoke(ShareRequest $request): JsonResponse
    {
        $share = Share::where('video_id', $request->video_id)->where('user_id', $this->user->id);

        if ($share->count() == 0) Share::create(['video_id' => $request->video_id, 'user_id' => $this->user->id]);

        return response()->json(['success' => true]);
    }
}
