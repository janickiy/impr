<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use URL;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        return view('cp.users.index')->with('title', 'Пользователи портала');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function status(Request $request)
    {
        $temp = [];

        foreach ($request->status as $id) {
            if (is_numeric($id)) {
                $temp[] = $id;
            }
        }

        switch ($request->action) {
            case  0 :

                User::whereIN('id', $temp)->update(['banned_at' => Carbon::now()]);

            case  1 :

                User::whereIN('id', $temp)->update(['banned_at' => null]);

                break;

            case 2 :

                User::whereIN('id', $temp)->update(['role' => 'author']);

                break;
        }

        return redirect(URL::route('cp.users.index'))->with('success', trans('message.actions_completed'));
    }
}
