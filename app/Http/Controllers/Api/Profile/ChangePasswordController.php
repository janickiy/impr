<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;

/**
 * @group Авторизация/Регистрация (authentication/registration).
 */
class ChangePasswordController extends Controller
{
    /**
     * Изменить пароль (change password).
     *
     * @authenticated
     *
     * @response 204 {}
     *
     * @param ChangePasswordRequest $request
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->get('password'), $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => trans('auth.password'),
            ]);
        }

        $request->user()->update([
            'password' => bcrypt($request->get('new_password')),
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
