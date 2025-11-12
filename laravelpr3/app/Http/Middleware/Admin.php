<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->admin != 1) {
            return redirect('/')->with('error', 'Je hebt geen toegang tot deze pagina.');
        }

        return $next($request);
    }
}
