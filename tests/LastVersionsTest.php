<?php

namespace Buttress\Browserslist\Tests;

class LastVersionsTest extends TestCase implements DataProvider
{
    use ListSetup;

    public function getData()
    {
        return [
            'ie' => [
                'name' => 'ie',
                'released' => ['9', '10', '11'],
                'versions' => ['9', '10', '11']
            ],
            'edge' => [
                'name' => 'edge',
                'released' => ['12'],
                'versions' => ['12', '13']
            ],
            'chrome' => [
                'name' => 'chrome',
                'released' => ['37', '38', '39'],
                'versions' => ['37', '38', '39', '40']
            ],
            'blackberry' => [
                'name' => 'blackberry',
                'released' => ['8'],
                'versions' => []
            ],
            'firefox' => [
                'name' => 'blackberry',
                'released' => [],
                'versions' => []
            ],
            'safari' => [
                'name' => 'blackberry',
                'released' => [],
                'versions' => []
            ],
            'opera' => [
                'name' => 'blackberry',
                'released' => [],
                'versions' => []
            ],
            'android' => [
                'name' => 'blackberry',
                'released' => [],
                'versions' => []
            ]
        ];
    }

    public function testLastVersionForAllBrowsers()
    {
        $this->assertSortedSame(
            ['chrome 39', 'edge 12', 'ie 11'],
            $this->list->query('last 1 version')
        );
    }

    public function testPluralization()
    {
        $this->assertSortedSame(
            ['chrome 39', 'chrome 38', 'edge 12', 'ie 11', 'ie 10'],
            $this->list->query('last 2 versions')
        );
    }

    public function testCaseSensitivity()
    {
        $this->assertSortedSame(
            ['chrome 39', 'chrome 38', 'edge 12', 'ie 11', 'ie 10'],
            $this->list->query('LAST 02 Version')
        );
    }
}
