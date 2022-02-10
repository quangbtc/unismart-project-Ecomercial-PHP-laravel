<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class CheckAdmin
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
        if (Auth::user()->is_admin == 1) {
            return $next($request);
        }else {
            $request->session()->flush();
            return redirect()->route('login')->with('status','Bạn không phải là admin, Nên không có quyển truy cập!');
        }
        
       
    }
}
