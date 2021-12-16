<?php
namespace Spacebib\PeformanceStats\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spacebib\PeformanceStats\PeformanceStatsServiceProvider;
use Spacebib\PeformanceStats\Storage\Storage;

class FeatureTestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(Storage::class, function ($app){
            return new CacheStorage($app, ['prefix' => $this->getStoragePrefix()]);
        });
    }

    public function getStoragePrefix(): string
    {
        return 'test';
    }

    protected function getPackageProviders($app)
    {
        return [
            PeformanceStatsServiceProvider::class,
        ];
    }

    public function getStorageKey(
        string $type,
        string $routeName,
        string $method,
        int $responseStatus
    ): string {
        return sprintf("%s.%s.%s.%s.%s",
                $this->getStoragePrefix(),
                $type,
                $routeName,
                strtolower($method),
                $responseStatus
            );

    }
}
