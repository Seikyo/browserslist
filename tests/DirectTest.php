<?php

namespace Buttress\Browserslist\Tests;

class DirectTest extends TestCase
{

    use ListSetup;

    public function testGetByName()
    {
        $this->assertSortedSame(['ie 10'], $this->list->query('ie 10'));
    }

    public function testCaseInsensitiveAlias()
    {
        $this->assertSortedSame($this->list->query('ie 10'), $this->list->query('Explorer 10'));
        $this->assertSortedSame($this->list->query('ie 10'), $this->list->query('IE 10'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown version 5 of FakeBrowser.
     */
    public function testErrorOnUnknownName()
    {
        $this->list->query('FakeBrowser 5');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown version 9.9 of ie.
     */
    public function testErrorOnUnknownVersion()
    {
        $this->list->query('ie 9.9');
    }

    public function testCanIUseData()
    {
        $this->assertSortedSame(['ios_saf 7.0-7.1'], $this->list->query('ios 7.0'));
        $this->assertSortedSame(['ios_saf 7.0-7.1'], $this->list->query('ios 7.1'));
    }

    public function testVersionHandlesExtraZero()
    {
        $this->assertSortedSame(['ios_saf 7.0-7.1'], $this->list->query('ios 7'));
        $this->assertSortedSame(['ios_saf 8'], $this->list->query('ios 8.0'));
    }

    public function testSupportsSafariTP()
    {
        $this->assertSortedSame(['safari TP'], $this->list->query('safari tp'));
        $this->assertSortedSame(['safari TP'], $this->list->query('Safari Tp'));
    }

}
