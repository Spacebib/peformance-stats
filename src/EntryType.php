<?php
namespace Spacebib\PeformanceStats;

class EntryType
{
    public const REQUEST = 'request';

    public static function get(): array
    {
        return [
            self::REQUEST
        ];
    }

    public static function has(string $value)
    {
        return in_array($value, self::get());
    }
}