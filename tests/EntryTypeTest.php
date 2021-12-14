<?php
namespace Spacebib\PeformanceStats\Tests;

use Spacebib\PeformanceStats\EntryType;

class EntryTypeTest extends FeatureTestCase
{
    public function test_func_has_succeed()
    {
        $this->assertTrue(EntryType::has(EntryType::REQUEST));
    }

    public function test_func_has_failed()
    {
        $this->assertFalse(EntryType::has("test"));
    }
}