<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!(auth()->guard('admin')->user()->Status == 'مفعل'))
        {
            Session::flush();
            return  redirect()->back()->with(['error'=>'هذا الحساب معطل']);
        }else
            return $next($request);
    }
}
