<?php
namespace Spacebib\PeformanceStats\Tests;

use Spacebib\PeformanceStats\Storage\Storage;
use Tests\TestCase;

class FeatureTestCase extends TestCase
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
