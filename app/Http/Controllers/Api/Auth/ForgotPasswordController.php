<?php

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Response;
use App\Events\ForgotPassword;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;

/**
 * @group Авторизация/Регистрация (authentication/registration).
 */
class ForgotPasswordController extends Controller
{
    /**
     * Забыл пароль (forgot password).
     *
     *
     * @param ForgotPasswordRequest $request
     *
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        event(new ForgotPassword($user));

        return response()->json(['success' => true]);
    }
}
