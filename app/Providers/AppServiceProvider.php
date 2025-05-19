<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use App\Services\FileBasedMaintenanceMode;
use Illuminate\Contracts\Foundation\MaintenanceMode as MaintenanceModeContract;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the filesystem
        $this->app->singleton('files', function () {
            return new Filesystem;
        });

        // Register service providers
        $this->app->register(FilesystemServiceProvider::class);
        $this->app->register(ArtisanServiceProvider::class);
        $this->app->register(ComposerServiceProvider::class);
        $this->app->register(ConsoleSupportServiceProvider::class);

        // Register the cache manager
        $this->app->singleton('cache', function ($app) {
            return new CacheManager($app);
        });

        // Register the cache store
        $this->app->singleton('cache.store', function ($app) {
            $filesystem = $app['files'];
            $store = new FileStore($filesystem, storage_path('framework/cache/data'));
            return new Repository($store);
        });

        // Register maintenance mode
        $this->app->singleton(MaintenanceModeContract::class, function ($app) {
            return new FileBasedMaintenanceMode(
                $app->storagePath().'/framework/down'
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
