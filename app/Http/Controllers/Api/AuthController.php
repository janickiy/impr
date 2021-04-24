<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Users};
use Illuminate\Support\Facades\Validator;
use Hash;
use URL;
use Auth;

use App\Helpers\ResponseHelpers;


class AuthController extends Controller
{
    public function check()
    {
        $user = Auth::user('web');

        if ($user) {
            return ResponseHelpers::jsonResponse(['key' => encrypt($user->id)], 200, true);
        }

        return ResponseHelpers::jsonResponse([], 200, true);
    }

    /**
     * @param $key
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function auth($key)
    {
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
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
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

            if(!$auth){
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


    }
}
