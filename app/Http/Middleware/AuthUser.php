<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redirect;
use Closure;

class AuthUser
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
        if (auth()->check()) {
            return $next($request);
        }
        return Redirect::to(env('ADMIN_PORTAL_URL'));
    }
}
