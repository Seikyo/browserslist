<?php

namespace Buttress\Browserslist\Tests;

class OperaTest extends TestCase
{
    use ListSetup;

    public function testOperaMini()
    {
        $this->assertSortedSame(['op_mini all'], $this->list->query('op_mini all'));
    }

    public function testOperaMiniCaseInsensitive()
    {
        $this->assertSortedSame(['op_mini all'], $this->list->query('OperaMini all'));
    }
}
