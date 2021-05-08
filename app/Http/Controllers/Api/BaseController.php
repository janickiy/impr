<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;

class BaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user('web');
            if (!$this->user) {
                return response()->json(['error' => true, 'message' => 'Auth required'], 401);
            }
            return $next($request);
        });
    }
}
