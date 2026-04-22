<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
   
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
         if(Auth::check()) {
            Cache::put(
                'user-is-online-' .
                Auth::id(),
                            true,
                            now()->addMinutes(2) // duree de presence
            );
        }

        return $next($request);
    }
}
