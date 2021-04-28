<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\{Users, Subscriptions};
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
    
    pu
}
