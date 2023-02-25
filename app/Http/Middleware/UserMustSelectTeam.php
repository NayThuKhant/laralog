<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserMustSelectTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->currentTeam) {
            return Inertia::render('Error', ['code' => 'CHOOSE_OR_CREATE_A_TEAM', 'message' => 'Please select or create a team to continue']);
        }

        return $next($request);
    }
}
