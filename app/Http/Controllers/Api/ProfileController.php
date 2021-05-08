<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Api\User\{ChangePasswordRequest, UserEditRequest,};
use App\Models\{User};
use Carbon\Carbon;
use Auth;
use Hash;

class ProfileController extends BaseController
{
    /**
     * Редактирование настроек
     * @param UserEditRequest $request
     * @return JsonResponse
     */
    public function edit(UserEditRequest $request): JsonResponse
    {
        $user = User::find($this->user->id);
        $user->email = $request->input('email');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->nickname = $request->input('nickname');
        $user->birthday = $request->input('birthday');
        $user->gender =  $request->input('gender',3);
        $user->contacts = $request->input('contacts');
        $user->settings = $request->input('settings');
        $user->save();

        return response()->json(['success' => true]);

    }

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->get('password'), $this->user->password)) {
            return response()->json(['error' => true, 'message' => trans('auth.invalid_password')], Response::HTTP_BAD_REQUEST);
        }

        $this->user->update([
            'password' => bcrypt($request->get('new_password')),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function delete($code): JsonResponse
    {
        $userCode = Redis::get('user_code_' . $this->user->id);

        if ($userCode && $userCode == $code) {
            $user = User::find('id', $this->user->id);
            $user->deleted_at = Carbon::now();
            $user->save();

            Auth::guard('web')->logout();

            return response()->json(['success' => true]);
        }
    }
}
