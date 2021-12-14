<?php

namespace Spacebib\PeformanceStats;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Spacebib\PeformanceStats\Exceptions\DriverNotFoundException;
use Spacebib\PeformanceStats\Storage\Storage;

class PeformanceStatsServiceProvider extends ServiceProvider
{
    public function register():void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'peformancestats'
        );
    }

    public function boot(): void
    {
        if (! config('peformancestats.enabled')) {
            return;
        }
        $this->registerServices();
        $this->registerPublishing();
        Application::start($this->app);
        Application::listenForStorageOpportunities($this->app);
    }

    protected function registerServices():void
    {
        $this->app->singleton(Storage::class, function ($app){

            $storage = $app['config']->get('peformancestats.storage');

            $driver = Arr::get($storage, Arr::get($storage, 'default'));

            if (! $driverClass = Arr::get($driver, 'class')) {
                throw new DriverNotFoundException("Driver {$driver} does not exist!");
            }
            return new $driverClass($app, $config = $driver);
        });

        $this->app->alias(Storage::class, "peformancestats.storage");
    }

    private function registerPublishing():void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('peformancestats.php'),
            ], 'peformancestats-config');
        }
    }
}