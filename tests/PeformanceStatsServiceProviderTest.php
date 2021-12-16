<?php
namespace Spacebib\PeformanceStats\Tests;

use Spacebib\PeformanceStats\Storage\Storage;

class PeformanceStatsServiceProviderTest extends FeatureTestCase
{
    public function test_it_binds_storage_class()
    {
        $this->assertInstanceOf(Storage::class, app('peformancestats.storage'));
    }
}