<?php

namespace Buttress\Browserslist\Tests;

use Buttress\Browserslist\Browserslist;

class GlobalStatsTest extends TestCase
{

    use ListSetup;

    protected function populateList(Browserslist $list)
    {
        $list->setUsage(collect([
            'global' => [
                'ie 8' => 0.1,
                'ie 9' => 5,
                'ie 10' => 10.1,
                'ie 11' => 75
            ]
        ]));

        return $list;
    }

    public function testSelectByPopularity()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('> 10%'));
    }

    public function testOptionalSpace()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('>10%'));
    }

    public function testFloat()
    {
        $this->assertSortedSame(['ie 11'], $this->list->query('>10.1%'));
        $this->assertSortedSame(['ie 11', 'ie 10', 'ie 9'], $this->list->query('>.2%'));
    }

}
