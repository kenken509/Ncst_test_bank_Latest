<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');
            $now = Carbon::now();

            Log::info('SessionTimeout Middleware - Last Activity: ', ['lastActivity' => $lastActivity]);
            if ($lastActivity && $now->diffInMinutes($lastActivity) > config('session.lifetime')) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect()->route('login.show')->with('error', 'Your session has expired due to inactivity.');
            }

            session(['lastActivityTime' => $now]);
        }

        return $next($request);
    }
}
