<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Users};
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelpers;
use Hash;
use URL;
use Auth;

class AuthController extends Controller
{
    /**
     * Проверка авторизован ли пользователь
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function check()
    {
        try {
            $user = Auth::user('web');

            if ($user) {
                return ResponseHelpers::jsonResponse(['key' => encrypt($user->id)], 200, true);
            }

            return ResponseHelpers::jsonResponse([], 200, true);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Токен авторизация
     * @param $key
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function auth($key)
    {
        try {
            $id = decrypt($key);
            $user = Users::find($id);

            if (!$user) {
                return ResponseHelpers::jsonResponse([], 404);
            }

            $oldSessionId = session()->getId();

            auth('web')->login($user);

            if (session()->getId() !== $oldSessionId) {
                session()->put('prevSession', $oldSessionId);
            }
            return ResponseHelpers::jsonResponse(['email' => $user->email], 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Авторизаця
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|string',
                    'password' => 'required|string'
                ]
            );

            if ($validator->fails()) {
                return ResponseHelpers::jsonResponse([
                    'error' => $validator->messages()
                ], 400);
            }

            if ($user = app('auth')->getProvider()->retrieveByCredentials($request->only('email', 'password'))) {
                $oldSessionId = session()->getId();

                $auth = Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember);

                if (!$auth) {
                    return ResponseHelpers::jsonResponse([
                        'error' => trans('auth.failed')
                    ], 401);
                }

                if (session()->getId() !== $oldSessionId) {
                    session()->put('prevSession', $oldSessionId);
                }

                return ResponseHelpers::jsonResponse(['id' => $user->id, 'key' => encrypt($user->userId)], 200, true);
            }

            return ResponseHelpers::jsonResponse([
                'error' => trans('auth.failed')
            ], 401);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Authorized User Data
     * [данные пользователя]
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function info()
    {
        try {
            $user = Auth::user('web');

            if (!$user) {
                return ResponseHelpers::jsonResponse([], 404);
            }

            return ResponseHelpers::jsonResponse($user);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Регистрация
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registration(Request $request)
    {
        try {

            $user = Auth::user('web');

            if ($user) {
                return ResponseHelpers::jsonResponse([], 403, true);
            }

            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|min:6',
                    'confirm_password' => 'required|min:6|same:password',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'birthday' => 'date_format:Y-m-d',
                ]
            );

            if ($validator->fails()) {
                return ResponseHelpers::jsonResponse([
                    'error' => $validator->messages()
                ], 400);
            }

            Users::create([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'secondname' => $request->input('secondname'),
                'birthday' => $request->input('birthday'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'role' => 0,
                'password' => app('hash')->make($request->password),
                'contacts' => [
                    'email' => $request->email,
                    'phone' => $request->input('phone'),
                    'skype' => $request->input('skype'),
                ],
                'provider' => 'web'
            ]);

            return ResponseHelpers::jsonResponse(['message' => 'Регистрация успешно выполнена']);

        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Выход
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        return ResponseHelpers::jsonResponse(true, 200, true);
    }
}
