<?php
namespace Spacebib\PeformanceStats\Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Spacebib\PeformanceStats\IncomingEntry;
use Spacebib\PeformanceStats\Storage\Storage;

class CacheStorage extends Storage
{
    public function getPrefix(): string
    {
        return Arr::get($this->config, 'prefix');
    }


    public function storeRequest(IncomingEntry $incomingEntry): void
    {
        $content = $incomingEntry->content;
        if (!Arr::get($content,'route_name')) {
            return;
        }
        $stat = sprintf("%s.%s.%s.%s.%s",
            $this->getPrefix(),
            $incomingEntry->type,
            Arr::get($content,'route_name'),
            strtolower(Arr::get($content,'method')),
            Arr::get($content,'response_status')
        );
        $value = Cache::get($stat) ?? [];
        $value[] = $incomingEntry;
        Cache::put($stat, $value);
    }
}