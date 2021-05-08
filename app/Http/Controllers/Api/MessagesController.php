<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Messages\{MessagesRequest, MessageListRequest};
use App\Models\Messages;
use Illuminate\Http\Response;

class MessagesController extends BaseController
{
    /**
     * @param MessagesRequest $request
     * @return JsonResponse
     */
    public function send(MessagesRequest $request): JsonResponse
    {
        $insertId = Messages::create(array_merge($request->all(), ['sender_id' => $this->user->id, 'status' => 0]))->id;

        return response()->json(['success' => true, 'id' => $insertId, Response::HTTP_CREATED]);

    }

    /**
     * @param MessageListRequest $request
     * @return JsonResponse
     */
    public function getList(MessageListRequest $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $page = ($page - 1) * $limit;

        $receiver_id = $request->receiver_id;

        $items = [];
        $messages = Messages::whereRaw("sender_id=" . $this->user->id . " AND receiver_id=" . $receiver_id . " AND status IN (0,1,3)) OR (sender_id=" . $receiver_id . " AND id_receiver=" . $this->user->id . " AND status IN (0,1,2)");
        $count = $messages->count();

        foreach ($messages->limit($limit)->offset($page)->orderBy('created_at', 'desc')->get() as $row) {
            $items[] = [
                'id' => $row->id,
                'message' => $row->message,
                'status' => $row->status,
                'sender_id' => $row->sender_id,
                'created_at' => $row->created_at,

            ];
        }

        return response()->json(['items' => $items, 'count' => $count]);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $message = Messages::whereRaw("id=$id AND (receiver_id=" . $this->user->id . " OR sender_id=" . $this->user->id . ")");

        if (!$message) return response()->json([], Response::HTTP_NOT_FOUND);

        $status = null;

        switch ($message->status) {
            case 0:
            case 1:

                if ($message->sender_id == $this->user->id)
                    $status = 2;
                else if ($message->receiver_id == $this->user->id)
                    $status = 3;

                break;

            case 2:

                if ($message->receiver_id == $this->user->id) $status = 4;

                break;

            case 3:

                if ($message->sender_id == $this->user->id) $status = 4;

                break;

        }

        if ($status) {
            $message->status = $status;
            $message->save();
        }

        return response()->json(['success' => true]);
    }

}
