<?php
namespace Spacebib\PeformanceStats\Tests\Storage;

use DataDog\DogStatsd;
use Spacebib\PeformanceStats\EntryType;
use Spacebib\PeformanceStats\Exceptions\InvalidEntryTypeException;
use Spacebib\PeformanceStats\IncomingEntry;
use Spacebib\PeformanceStats\Storage\DataDogStorage;
use Tests\TestCase;

class DataDogStorageTest extends TestCase
{
    public function getInstance(): DataDogStorage
    {
        return new DataDogStorage($this->app, $this->app['config']->get('peformancestats.storage.datadog'));
    }
    public function test_getStatsd()
    {
        $dataDogStorage = $this->getInstance();

        $this->assertInstanceOf(DogStatsd::class, $dataDogStorage->getStatsd());

        $this->assertSame($this->app['config']->get('peformancestats.storage.datadog.prefix'), $dataDogStorage->getPrefix());
    }

    public function test_store_failed()
    {
        $dataDogStorage = $this->getInstance();
        $entry = new IncomingEntry([
            'test' => 'ss'
        ]);
        $entry->type('ss');
        $this->expectException(InvalidEntryTypeException::class);
        $dataDogStorage->store([$entry]);
    }

    public function test_store_succeed()
    {
        $dataDogStorage = $this->getInstance();
        $entry = new IncomingEntry([
            'url' => 'test',
            'route_name' => 'test',
            'method' => 'POST',
            'response_status' => 200,
            'duration' => 100,
        ]);

        $entry->type(EntryType::REQUEST);

        $dataDogStorage->store([$entry]);

        $this->assertEquals(1, 1);
    }

    public function test_it_not_have_route_name()
    {
        $dataDogStorage = $this->getInstance();
        $entry = new IncomingEntry([
            'url' => 'test',
            'route_name' => null,
            'method' => 'POST',
            'response_status' => 200,
            'duration' => 100,
        ]);
        $this->assertFalse($dataDogStorage->hasRouteName($entry));
    }

    public function test_it_have_route_name()
    {
        $dataDogStorage = $this->getInstance();
        $entry = new IncomingEntry([
            'url' => 'test',
            'route_name' => 'test',
            'method' => 'POST',
            'response_status' => 200,
            'duration' => 100,
        ]);
        $this->assertTrue($dataDogStorage->hasRouteName($entry));
    }
}