<?php

namespace Buttress\Browserslist\Tests;

use Buttress\Browserslist\BrowserslistFactory;

class CustomStatsTest extends TestCase
{

    public function testCustomStats()
    {
        $factory = new BrowserslistFactory();
        $list = $factory->createWithData([
            'stats' => __DIR__ . '/fixtures/stats.json'
        ]);

        $this->assertSortedSame(['ie 11'], $list('>10% in my stats'));
    }
}
