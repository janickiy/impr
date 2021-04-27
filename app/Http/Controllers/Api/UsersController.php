<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Users, Subscriptions};
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelpers;
use Auth;

class UsersController extends Controller
{
    /**
     * Редактирование настроек
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addSettings(Request $request)
    {
        try {

            $user = Auth::user('web');

            if (!$user) {
                return ResponseHelpers::jsonResponse(['error' => 'Вы не авторизированы!'], 401, true);
            }

            $rules = [
                'settings' => 'json',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return ResponseHelpers::jsonResponse([
                    'error' => $validator->messages()
                ], 400);
            }

            Users::where('id', $user->id)->update(['settings' => $request->settings]);

            return ResponseHelpers::jsonResponse(['success' => true], 200);

        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Подписка
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        try {
            $user = Auth::user('web');

            if (!$user) {
                return ResponseHelpers::jsonResponse(['error' => 'Вы не авторизированы!'], 401, true);
            }

            $count = Subscriptions::where('subscription_id', $request->subscription_id)->where('user_id', $user->i)->count();

            if ($count > 0) return ResponseHelpers::jsonResponse(['error' => 'Вы уже подписаны!'], 400, true);

            Subscriptions::create(['subscription_id' => $request->subscription_id, 'user_id' => $user->id]);

            return ResponseHelpers::jsonResponse(['success' => true], 200);

        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Отписка
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        try {
            $user = Auth::user('web');

            if (!$user) {
                return ResponseHelpers::jsonResponse(['error' => 'Вы не авторизированы!'], 401, true);
            }

            Subscriptions::where('subscription_id', $request->subscription_id)->where('user_id', $user->i)->delete();

            return ResponseHelpers::jsonResponse(['success' => true], 200);

        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
