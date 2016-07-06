<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

/**
 * A simple middleware to test if
 * an authenticated user is an admin
 *
 * Class Admin
 * @package App\Http\Middleware
 */
class Admin
{
    /**
     * Check administrator rights, or else
     * redirects to login page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        return redirect('/login');
    }
}
