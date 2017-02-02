<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin_user')
    {
        if ( ! Auth::guard($guard)->check()){
            return redirect('admin/login')->with('status', 401);
        }
        return $next($request);
    }
}
