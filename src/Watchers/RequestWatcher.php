<?php
namespace Spacebib\PeformanceStats\Watchers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Spacebib\PeformanceStats\IncomingEntry;

class RequestWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param  Application  $app
     * @return void
     */
    public function register(Application $app): void
    {
        $app['events']->listen(RequestHandled::class, [$this, 'recordRequest']);
    }

    public function recordRequest(RequestHandled $event): void
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $event->request->server('REQUEST_TIME_FLOAT');

        \Spacebib\PeformanceStats\Application::recordRequest(
            IncomingEntry::make([
                'url' => $event->request->fullUrl(),
                'route_name' => optional($event->request->route())->getName(),
                'method' => $event->request->method(),
                'response_status' => $event->response->getStatusCode(),
                'duration' => $startTime ? floor((microtime(true) - $startTime) * 1000) : null,
            ])
        );
    }
}
