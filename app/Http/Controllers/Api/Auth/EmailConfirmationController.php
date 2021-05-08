<?php

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Response;
use App\Models\Permission\Role;
use App\Models\Auth\EmailConfirm;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * @group Авторизация/Регистрация (authentication/registration).
 */
class EmailConfirmationController extends Controller
{
    /**
     * Потверждение email (email confirmation).
     *
     * @urlParam token string required токен подтверждения
     *
     * @response 204 {}
     *
     * @param EmailConfirm $confirm
     *
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function __invoke(EmailConfirm $confirm): JsonResponse
    {
        DB::transaction(function () use ($confirm) {
            $user = User::firstWhere('email', $confirm->email);
            $role = Role::firstWhere('name', Role::ROLE_USER);
            $user->syncRoles($role);
            $user->syncPermissions($role->permissions);

            $confirm->delete();
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
