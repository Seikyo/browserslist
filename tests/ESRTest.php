<?php

namespace Buttress\Browserslist\Tests;

class ESRTest extends TestCase
{

    use ListSetup;

    public function testFirefoxESR()
    {
        $list = $this->list;

        $result = $list('Firefox ESR');
        $this->assertSortedSame($result, $list('firefox esr'));
        $this->assertSortedSame($result, $list('ff esr'));
        $this->assertSortedSame($result, $list('fx esr'));
    }
}
