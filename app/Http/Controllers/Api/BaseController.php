<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Users, Subscriptions};
use App\Helpers\ResponseHelpers;
use Auth;

class BaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        try {
            $this->middleware(function ($request, $next) {

               $this->user = Auth::user('web');
                if (!$this->user) {
                    return ResponseHelpers::jsonResponse(['error' => true, 'message' => 'Auth required'], 401);
                }
                return $next($request);
            });
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
