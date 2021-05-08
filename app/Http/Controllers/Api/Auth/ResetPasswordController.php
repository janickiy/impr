<?php

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Auth\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;

/**
 * @group Авторизация/Регистрация (authentication/registration).
 */
class ResetPasswordController extends Controller
{
    /**
     * Сброс пароля (reset password).
     *
     * @response 204 {}
     *
     * @param ResetPasswordRequest $request
     *
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $reset = PasswordReset::firstWhere('token', $request->get('token'));
        $user = User::whereEmail($reset->email)->firstOrFail();

        DB::transaction(function () use ($request, $user) {
            $user->tokens()->delete();
            $user->update(['password' => bcrypt($request->get('password'))]);
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
