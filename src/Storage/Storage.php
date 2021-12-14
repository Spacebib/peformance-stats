<?php
namespace Spacebib\PeformanceStats\Storage;

use Illuminate\Contracts\Foundation\Application;
use Spacebib\PeformanceStats\EntryType;
use Spacebib\PeformanceStats\Exceptions\InvalidEntryTypeException;
use Spacebib\PeformanceStats\IncomingEntry;

abstract class Storage
{
    /**
     * @var Application
     */
    protected Application $application;

    protected array $config;

    public function __construct(Application $application, array $config)
    {
        $this->application = $application;
        $this->config = $config;
    }


    public function store(array $incomingEntries): void
    {
        /** @var IncomingEntry $incomingEntry */
        foreach ($incomingEntries as $incomingEntry) {
            if (! EntryType::has($incomingEntry->type)) {
                throw new InvalidEntryTypeException("Type $incomingEntry->type validation error!");
            }
            $method = 'store'. ucfirst($incomingEntry->type);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$incomingEntry]);
            }
        }
    }

    abstract public function storeRequest(IncomingEntry $incomingEntry):void;
}