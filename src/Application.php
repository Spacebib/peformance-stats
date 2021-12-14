<?php
namespace Spacebib\PeformanceStats;

use Illuminate\Contracts\Foundation\Application as App;
use Spacebib\PeformanceStats\Storage\Storage;

class Application
{
    /**
     * @var array
     */
    public static array $watchers;

    /**
     * The list of queued entries to be stored.
     *
     * @var IncomingEntry[]
     */
    public static array $entriesQueue = [];


    public static function start(App $app): void
    {
        if (!config('peformancestats.enabled')) {
            return;
        }
        static::registerWatchers($app);
    }

    public static function registerWatchers(App $app): void
    {
        foreach (config('peformancestats.watchers') as $key => $watcher) {
            if (is_string($key) && $watcher === false) {
                continue;
            }

            if (is_array($watcher) && ! ($watcher['enabled'] ?? true)) {
                continue;
            }

            $watcher = $app->make(is_string($key) ? $key : $watcher, [
                'options' => is_array($watcher) ? $watcher : [],
            ]);

            static::$watchers[] = get_class($watcher);

            $watcher->register($app);
        }
    }

    /**
     * Determine if a given watcher has been registered.
     *
     * @param  string  $class
     * @return bool
     */
    public static function hasWatcher(string $class): bool
    {
        return in_array($class, static::$watchers);
    }

    /**
     * Record the given entry.
     *
     * @param IncomingEntry $entry
     * @return void
     */
    public static function recordRequest(IncomingEntry $entry): void
    {
        static::record(EntryType::REQUEST, $entry);
    }

    /**
     * @param  string  $type
     * @param  IncomingEntry  $entry
     */
    protected static function record(string $type, IncomingEntry $entry): void
    {
        $entry->type($type);
        static::$entriesQueue[] = $entry;
    }

    /**
     * @param  App  $app
     */
    public static function listenForStorageOpportunities(App $app): void
    {
        static::storeEntriesBeforeTermination($app);
    }

    /**
     * Store the entries in queue before the application termination.
     *
     * This handles storing entries for HTTP requests and Artisan commands.
     *
     * @param  App  $app
     * @return void
     */
    protected static function storeEntriesBeforeTermination(App $app): void
    {
        $app->terminating(function () use ($app) {
            static::store(app('peformancestats.storage'));
        });
    }

    public static function store(Storage $storage)
    {
        $storage->store(self::$entriesQueue);
        static::$entriesQueue = [];
    }
}