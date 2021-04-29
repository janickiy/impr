<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\{Users, Subscriptions, Likes, Messages};
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelpers;
use Carbon\Carbon;
use Auth;
use Redis;

class UsersController extends BaseController
{
    /**
     * Редактирование настроек
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function editUser(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255|unique:users',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'secondname' => 'max:255',
            'birthday' => 'date_format:Y-m-d',
            'contacts' => 'json',
            'settings' => 'json',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseHelpers::jsonResponse([
                'error' => $validator->messages()
            ], 400);
        }

        $user = Users::find('id', $this->user->id);
        $user->email = $request->input('email');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->secondname = $request->input('secondname');
        $user->birthday = $request->input('birthday');
        $user->contacts = $request->input('contacts');
        $user->settings = $request->input('settings');
        $user->save();

        return ResponseHelpers::jsonResponse(['success' => true]);

    }

    /**
     * Подписка
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $count = Subscriptions::where('subscription_id', $request->subscription_id)->where('user_id', $this->user->id)->count();

        if ($count > 0) return ResponseHelpers::jsonResponse(['error' => 'Вы уже подписаны!'], 400, true);

        Subscriptions::create(['subscription_id' => $request->subscription_id, 'user_id' => $this->user->id]);

        return ResponseHelpers::jsonResponse(['success' => true]);
    }

    /**
     * Отписка
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        Subscriptions::where('subscription_id', $request->subscription_id)->where('user_id', $this->user->id)->delete();

        return ResponseHelpers::jsonResponse(['success' => true]);

    }

    /**
     * @param $code
     * @return mixed
     */
    public function delete($code)
    {
        $userCode = Redis::get('user_code_' . $this->user->id);

        if ($userCode && $userCode == $code) {
            $user = Users::find('id', $this->user->id);
            $user->deleted_at = Carbon::now();
            $user->save();

            Auth::guard('web')->logout();

            return ResponseHelpers::jsonResponse(['success' => true]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function like(Request $request)
    {
        $rules = [
            'content_type' => 'required',
            'content_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseHelpers::jsonResponse([
                'error' => $validator->messages()
            ], 400);
        }

        $likes = Likes::where('content_type','=',$request->content_type)->where('content_id',$request->content_id)->where('user_id', $this->user->id);

        if ($likes->count() > 0)
            $likes->delete();
        else
            Likes::create(['content_type' => $request->content_type, 'content_id' => $request->content_id, 'user_id' => $this->user->id]);

        return ResponseHelpers::jsonResponse(['success' => true]);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addComment(Request $request)
    {
        if ($request->parent_id)  {
            $rules = [
                'parent_id' =>'integer|exists:comments,id',
                'comment' => 'required',
            ];
        } else {
            $rules = [
                'video_id' =>'required|integer|exists:videos,id',
                'comment' => 'required',
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseHelpers::jsonResponse([
                'error' => $validator->messages()
            ], 400);
        }

        $insertId = Comments::create(array_merge($request->all(),['parent_id' => $request->input('parent_id',0), 'user_id' => $this->user->id]))->id;

        return ResponseHelpers::jsonResponse(['success' => true, 'id' => $insertId]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendMessage(Request $request)
    {
        $rules = [
            'receiver_id' =>'required|integer|exists:users,id',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseHelpers::jsonResponse([
                'error' => $validator->messages()
            ], 400);
        }

        $insertId = Messages::create(array_merge($request->all(),['sender_id' => $this->user->id, 'status' => 0]))->id;

        return ResponseHelpers::jsonResponse(['success' => true, 'id' => $insertId]);
    }

    public function getMessagesList($offset, $number, $id_sender, $id_receiver)
    {
        $query = "SELECT *, a.id AS id, b.id AS id_user, DATE_FORMAT(a.created_at,'%d.%m.%y %H:%i') as created FROM " . core::database()->getTableName('messages') . " a 
					LEFT JOIN " . core::database()->getTableName('users') . " b ON b.id=a.id_sender 
					WHERE (id_sender=" . $id_sender . " AND id_receiver=" . $id_receiver . " AND status IN (0,1,3)) OR (id_sender=" . $id_receiver . " AND id_receiver=" . $id_sender . " AND status IN (0,1,2)) 
					ORDER BY a.created_at DESC
					LIMIT " . $number . " OFFSET ".$offset." 
					
					";
    }


}
