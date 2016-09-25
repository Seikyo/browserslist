<?php

namespace Buttress\Browserslist\Tests;

class RangeTest extends TestCase implements DataProvider
{

    use ListSetup;

    public function getData()
    {
        return [
            'ie' => [
                'name' => 'ie',
                'released' => ['8', '9', '10', '11'],
                'versions' => ['8', '9', '10', '11']
            ]
        ];
    }

    public function testSelectRange()
    {
        $this->assertSortedSame(['ie 10', 'ie 9', 'ie 8'], $this->list->query('ie 8-10'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown browser Fakebrowser
     */
    public function testExceptionOnInvalidBrowser()
    {
        $this->list->query('Fakebrowser 1-10');
    }
}

/**
 * test('raises on an unknown browser', t => {
 * t.throws(function () {
 * browserslist('unknown 4-7');
 * }, 'Unknown browser unknown');
 * });
 */
