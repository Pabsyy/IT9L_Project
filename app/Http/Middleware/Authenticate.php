<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Rate limiting for authentication attempts
        $key = 'auth_attempts_'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) { // 5 attempts per minute
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => 'Too many authentication attempts. Please try again in '.$seconds.' seconds.'
            ], 429);
        }
        RateLimiter::hit($key, 60); // Keep record for 1 minute

        try {
            $this->authenticate($request, $guards);

            // Security headers
            $response = $next($request);
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
            
            // If using HTTPS
            if ($request->secure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            }

            return $response;

        } catch (AuthenticationException $e) {
            // Log failed authentication attempt
            \Log::warning('Failed authentication attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            throw $e;
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            return route('customer.login');
        }
        return null;
    }
} 