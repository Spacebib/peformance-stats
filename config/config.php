<?php
use Spacebib\PeformanceStats\Watchers;

return [
    'enabled' => env('PEFORMANCE_STATS_ENABLED', true),
    'watchers' => [
        Watchers\RequestWatcher::class => [
            'enabled' => env('PEFORMANCE_STATS_REQUEST_WATCHER', true),
        ],
    ],
    'storage' => [
        'default' => env('PEFORMANCE_STATS_STORAGE', 'datadog'),
        'datadog' => [
            'class' => \Spacebib\PeformanceStats\Storage\DataDogStorage::class,
            'prefix' => env('PEFORMANCE_STATS_STORAGE_PREFIX', 'peformancestats'),
            'host' => '127.0.0.1',
            'port' => 8125
        ]
    ]
];