<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailVerification\SendController;
use App\Models\EmailConfirmation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //TODO generate token with access based on user_role

    public function redirectToProvider($driver)
    {
        if (env('IS_ALPHA') && ($driver == 'facebook' || $driver == 'vkontakte')) {
            return response([
                'error' => 0,
                'message' => "login with $driver not supported"
            ], 404);
        }

        //if driver doesnt exist return 404
        if(empty(config('services.'.$driver))){
            return response([
                'error' => 0,
                'message' => "login with $driver not supported"
            ], 404);
        }

        return Socialite::driver($driver)->redirect();
    }

    public function test()
    {
        echo 'we';
    }


    public function loginWithSocial($driver)
    {
        $user_role = 1;
        $email_status = 1;
        if (env('IS_ALPHA')) {
            $user_role = 3;
        }

        $user_data = Socialite::driver($driver)->user();

        $id = $user_data->getId();
        $email = $user_data->getEmail();
        $email_verified = 1;

        $user = User::where('social_id', $id)->first();


        //register user
        if (!$user) {
            $user_role = 2;
            if (!$email) {
                $user_role = 1;
                $email_verified = 0;
                $email_status = 0;
            }
            $user = User::create([
                'email' => $email,
                'social_id' => $id,
                'social_type' => $driver,
                'email_verified' => $email_verified,
                'user_role' => $user_role
            ]);

            $token = $user->createToken('secretkey')->plainTextToken;

            $res = [
                'error' => 0,
                'data' => [
                    'token' => $token,
                    'email_status' => $email_status,
                    'user' =>$user,
                ]
            ];

            return response($res, 201);
        }

        //login user
        $token = $user->createToken('secretkey')->plainTextToken;

        $res = [
            'error' => 0,
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ];

        return response($res, 200);
    }

    public function sendEmail(Request $request)
    {
        $fields = $request;
        $email = $fields['email'];

        $user = Auth::user();
        $user->email = $email;
        $user->save();

        return EmailVerificationController::sendEmail($user['email']);
    }
}
