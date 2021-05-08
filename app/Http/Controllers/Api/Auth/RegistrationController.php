<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use App\Events\UserRegistered;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegistrationRequest;

/**
 * @group Авторизация/Регистрация (authentication/registration).
 */
class RegistrationController extends Controller
{
    /**
     * Регистрация.
     *
     * @param RegistrationRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(RegistrationRequest $request): JsonResponse
    {
        $data = $request->merge([
            'password' => bcrypt($request->get('password')),
        ])
            ->except(['tags']);

        $user = User::create($data);
        $user->tags()->sync($request->get('tags'));

        event(new UserRegistered($user));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
