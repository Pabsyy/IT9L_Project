<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MonitoringService;

class ApiMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (str_starts_with($request->path(), 'api/')) {
            MonitoringService::logApiRequest($request, $response);
        }

        return $response;
    }
} 