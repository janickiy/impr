<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\LikesRequest;
use App\Models\Likes;

class LikesController extends BaseController
{
    public function __invoke(LikesRequest $request): JsonResponse
    {
        $likes = Likes::where('content_type', '=', $request->content_type)->where('content_id', $request->content_id)->where('user_id', $this->user->id);

        if ($likes->count() > 0)
            $likes->delete();
        else
            Likes::create(['content_type' => $request->content_type, 'content_id' => $request->content_id, 'user_id' => $this->user->id]);

        return response()->json(['success' => true]);
    }

}
