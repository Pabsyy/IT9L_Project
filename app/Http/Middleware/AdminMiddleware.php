<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You must be an admin to access this area.']);
        }

        return $next($request);
    }
} 