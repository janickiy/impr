<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('cp.users.index')->with('title', 'Пользователи портала');
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
    }
}
