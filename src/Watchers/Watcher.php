<?php
namespace Spacebib\PeformanceStats\Watchers;

use Illuminate\Contracts\Foundation\Application;

abstract class Watcher
{
    /**
     * The configured watcher options.
     *
     * @var array
     */
    public $options = [];

    /**
     * Create a new watcher instance.
     *
     * @param  array  $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Register the watcher.
     *
     * @param  Application  $app
     * @return void
     */
    abstract public function register(Application $app);
}