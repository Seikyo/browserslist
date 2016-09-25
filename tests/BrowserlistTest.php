<?php

namespace Buttress\Browserslist\Tests;

class BrowserlistTest extends TestCase
{
    use ListSetup;

    public function testAcceptsArray()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query(['ie 11', 'ie 10']));
    }

    public function testAcceptsStringArray()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('ie 11, ie 10'));
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('ie 11,ie 10'));
    }

    public function testReturnsUnique()
    {
        $this->assertSortedSame(['ie 10'], $this->list->query('ie 10, ie 10'));
    }

    public function testReturnsEmptyResult()
    {
        $this->assertSortedSame([], $this->list->query(''));
        $this->assertSortedSame([], $this->list->query([]));
        $this->assertSortedSame([], $this->list->query(['','']));
    }

    public function testUsesDefaultQuery()
    {
        $this->assertSortedSame(
            $this->list->query($this->list->getDefaultQuery()),
            $this->list->query()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown browser query `test query`
     */
    public function testExceptionOnInvalidQuery()
    {
        $this->list->query('test query');
    }

    public function testSortsBrowsers()
    {
        $this->assertSame(
            ['firefox 10', 'firefox 9', 'ie 11', 'ie 10', 'ie 6'],
            $this->list->query(['ff 10', 'ie 11', 'ie 6', 'ie 10', 'ff 9'])->toArray()
        );
    }

    public function testReadmeExample()
    {
        $this->assertSortedSame(
            ['and_chr 51', 'chrome 52', 'chrome 56', 'edge 14', 'firefox 52', 'ie 11', 'ie_mob 11', 'ios_saf 10',
                'opera 41', 'safari TP'],
            $this->list->query('last 1 version, > 10%')
        );
    }

    public function testExcludesQueries()
    {
        $this->assertSortedSame(
            ['ie 10', 'ie 9'],
            $this->list->query('ie >= 9, not ie 11, not ie 10, ie 10')
        );
    }

    public function testCleanZeroVersion()
    {
        $this->assertFalse($this->list->query(['> 0%'])->contains('and_chr 0'));
    }
}
