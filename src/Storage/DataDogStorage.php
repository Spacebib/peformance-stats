<?php
namespace Spacebib\PeformanceStats\Storage;

use DataDog\DogStatsd;
use Illuminate\Support\Arr;
use Spacebib\PeformanceStats\IncomingEntry;

class DataDogStorage extends Storage
{
    public DogStatsd $statsd;

    public function getStatsd(): DogStatsd
    {
        if (isset($this->statsd) && $this->statsd instanceof DogStatsd) {
            return $this->statsd;
        }
        return new DogStatsd([
            'host' => Arr::get($this->config, 'host'),
            'port' => Arr::get($this->config, 'port'),
        ]);
    }

    public function getPrefix(): string
    {
        return Arr::get($this->config, 'prefix');
    }

    public function hasRouteName(IncomingEntry $incomingEntry): bool
    {
        return Arr::get($incomingEntry->content, "route_name") ? true : false;
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
        $this->getStatsd()->microtiming($stat, Arr::get($content,'duration'));
    }
}