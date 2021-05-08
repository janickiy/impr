<?php

namespace App\Http\Controllers\Api;

use App\Models\Messages;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Comments\{
    CommentsRequest,
    CommentsListRequest
};
use App\Models\Comments;
use Illuminate\Http\Response;

class CommentsController extends BaseController
{
    /**
     * @param CommentsListRequest $request
     * @return JsonResponse
     */
    public function getList(CommentsListRequest $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $page = ($page - 1) * $limit;

        $video_id = $request->video_id;

        $items = [];
        $comments = Comments::where('video_id',$video_id);
        $count = $comments->count();

        foreach ($comments->limit($limit)->offset($page)->orderBy('created_at', 'desc')->get() as $row) {
            $items[] = [
                'id' => $row->id,
                'comment' => $row->message,
                'parent_id' => $row->parent_id,
                'created_at' => $row->created_at,
            ];
        }

        return response()->json(['items' => $items, 'count' => $count]);
    }

    /**
     * @param CommentsRequest $request
     * @return JsonResponse
     */
    public function add(CommentsRequest $request): JsonResponse
    {
        $insertId = Comments::create(array_merge($request->all(),['parent_id' => $request->input('parent_id',0), 'user_id' => $this->user->id]))->id;

        return response()->json(['success' => true, 'id' => $insertId], Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        Comments::where('id',$id)->where('user_id', $this->user->id)->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
