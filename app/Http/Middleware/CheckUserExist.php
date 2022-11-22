<?php

namespace App\Http\Middleware;

use App\InvoiceSetting;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckUserExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = $request->route()->parameter('user_id');
        if (!isset($user_id) && empty($user_id)) {
            return response([
                "code"=>500,
                "msg"=>'User not found',
            ],500);
        }
        $user = User::find($user_id);
        
        if (!isset($user) && empty($user)) {
            return response([
                "code"=>500,
                "msg"=>'User not found',
            ],500);
        }
        return $next($request);
    }
}
