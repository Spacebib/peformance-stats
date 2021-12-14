<?php
namespace Spacebib\PeformanceStats;

class IncomingEntry
{
    /**
     * The entry's content.
     *
     * @var array
     */
    public array $content = [];

    /**
     * The entry's type.
     *
     * @var string
     */
    public string $type;

    /**
     * Create a new incoming entry instance.
     *
     * @param  array  $content
     * @return void
     */
    public function __construct(array $content)
    {
        $this->content = array_merge($content);
    }

    /**
     * Assign the entry a given type.
     *
     * @param  string  $type
     * @return IncomingEntry
     */
    public function type(string $type): IncomingEntry
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Create a new entry instance.
     *
     * @param  mixed  ...$arguments
     * @return static
     */
    public static function make(...$arguments): IncomingEntry
    {
        return new static(...$arguments);
    }
}