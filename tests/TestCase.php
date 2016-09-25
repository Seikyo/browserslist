<?php

namespace Buttress\Browserslist\Tests;

use Illuminate\Contracts\Support\Arrayable;

class TestCase extends \PHPUnit_Framework_TestCase
{

    public static $dataCache;
    public static $usageCache;
    public static $aliasCache;

    public function assertSortedSame($expected, $actual)
    {
        if ($expected instanceof Arrayable) {
            $expected = $expected->toArray();
        }
        if ($actual instanceof Arrayable) {
            $actual = $actual->toArray();
        }
        if (is_array($expected) && is_array($actual)) {
            sort($expected);
            sort($actual);
        }
        $this->assertSame($expected, $actual);
    }

}
