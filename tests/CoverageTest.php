<?php

namespace Buttress\Browserslist\Tests;

use Buttress\Browserslist\Browserslist;
use Illuminate\Support\Collection;

class CoverageTest extends TestCase //implements DataProvider
{

    use ListSetup;

    public function populateList(Browserslist $list)
    {
        $list->setUsage(Collection::make([
            'global' => [
                'ie 9' =>  5,
                'ie 10' => 10.1
            ],
            'UK' =>[
                'ie 9' =>  2,
                'ie 10' => 4.4
            ]
        ]));

        return $list;
    }

    public function testReturnsCoverage()
    {
        $this->assertEquals(15.1, $this->list->coverage(['ie 9', 'ie 10']));
    }

    public function testEmptyBrowsers()
    {
        $this->assertEquals(0, $this->list->coverage([]));
    }

}
