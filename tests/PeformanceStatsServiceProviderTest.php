<?php
namespace Spacebib\PeformanceStats\Tests;

use Spacebib\PeformanceStats\Storage\Storage;
use Tests\TestCase;

class PeformanceStatsServiceProviderTest extends  TestCase
{
    public function test_it_binds_storage_class()
    {
        $this->assertInstanceOf(Storage::class, app('peformancestats.storage'));
    }
}