<?php
namespace Spacebib\PeformanceStats\Tests\Watchers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Spacebib\PeformanceStats\EntryType;
use Spacebib\PeformanceStats\Tests\FeatureTestCase;
use Illuminate\Support\Facades\Route;

class RequestWatcherTest extends FeatureTestCase
{
    public function test_request_watcher_registers_requests()
    {
        Route::get('/emails', function () {
            return ['email' => 'xingxiang@spacebib.com'];
        })->name("emails");
        $this->get('/emails')->assertSuccessful();
        $entrys = Cache::get($this->getStorageKey('request', 'emails', 'get', 200));
        $entry = Arr::first($entrys);

        $this->assertSame(EntryType::REQUEST, $entry->type);
        $this->assertSame('GET', $entry->content['method']);
        $this->assertSame(200, $entry->content['response_status']);
        $this->assertSame('emails', $entry->content['route_name']);
    }

}