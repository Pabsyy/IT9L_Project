<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MonitoringService
{
    public static function logApiRequest($request, $response)
    {
        $data = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'status' => $response->status(),
            'duration' => defined('LARAVEL_START') ? (microtime(true) - LARAVEL_START) : 0,
            'user_agent' => $request->userAgent(),
        ];

        Log::channel('api')->info('API Request', $data);

        // Store metrics for monitoring
        self::storeMetrics($data);
    }

    public static function logError(\Exception $e, $context = [])
    {
        $data = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'context' => $context,
        ];

        Log::error('Application Error', $data);
    }

    protected static function storeMetrics($data)
    {
        $key = 'api_metrics:' . date('Y-m-d:H');
        
        Cache::remember($key, now()->addDay(), function () {
            return [
                'requests' => 0,
                'errors' => 0,
                'total_duration' => 0,
                'status_codes' => [],
            ];
        });

        Cache::increment("{$key}:requests");
        
        if ($data['status'] >= 400) {
            Cache::increment("{$key}:errors");
        }

        Cache::increment("{$key}:status_codes:{$data['status']}");
        Cache::increment("{$key}:total_duration", $data['duration']);
    }

    public static function getMetrics($timeframe = 'hour')
    {
        $key = 'api_metrics:' . date('Y-m-d:H');
        
        $metrics = Cache::get($key, [
            'requests' => 0,
            'errors' => 0,
            'total_duration' => 0,
            'status_codes' => [],
        ]);

        // Add database metrics
        $metrics['db_metrics'] = [
            'slow_queries' => DB::select("SELECT COUNT(*) as count FROM mysql.slow_log WHERE start_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)")[0]->count ?? 0,
            'connections' => DB::select("SHOW STATUS LIKE 'Threads_connected'")[0]->Value ?? 0,
        ];

        // Add cache metrics
        $metrics['cache_metrics'] = [
            'hits' => Cache::get('cache_hits', 0),
            'misses' => Cache::get('cache_misses', 0),
        ];

        return $metrics;
    }

    public static function checkSystemHealth()
    {
        $health = [
            'database' => self::checkDatabaseHealth(),
            'cache' => self::checkCacheHealth(),
            'storage' => self::checkStorageHealth(),
            'queue' => self::checkQueueHealth(),
        ];

        return $health;
    }

    protected static function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy'];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => $e->getMessage()
            ];
        }
    }

    protected static function checkCacheHealth()
    {
        try {
            Cache::store()->get('health_check');
            return ['status' => 'healthy'];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => $e->getMessage()
            ];
        }
    }

    protected static function checkStorageHealth()
    {
        $storage = storage_path();
        $freeSpace = disk_free_space($storage);
        $totalSpace = disk_total_space($storage);
        $usedPercentage = ($totalSpace - $freeSpace) / $totalSpace * 100;

        return [
            'status' => $usedPercentage < 90 ? 'healthy' : 'warning',
            'used_percentage' => $usedPercentage,
            'free_space' => $freeSpace,
        ];
    }

    protected static function checkQueueHealth()
    {
        try {
            $failedJobs = DB::table('failed_jobs')->count();
            return [
                'status' => $failedJobs === 0 ? 'healthy' : 'warning',
                'failed_jobs' => $failedJobs,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => $e->getMessage()
            ];
        }
    }
} 