<?php

namespace Buttress\Browserslist\Tests;

class BrowserTest extends TestCase implements DataProvider
{

    use ListSetup;

    public function getData()
    {
        return [
            'ie' => [
                'name' => 'ie',
                'released' => ['9', '10', '11'],
                'versions' => ['9', '10', '11']
            ]
        ];
    }

    public function testLastVersions()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('last 2 versions')->toArray());
    }

    public function testVersionSelect()
    {
        $this->assertSortedSame(['ie 11', 'ie 10'], $this->list->query('last 2 ie versions')->toArray());
    }

    public function testPluralization()
    {
        $this->assertSortedSame(['ie 11'], $this->list->query('last 1 ie version')->toArray());
    }

    public function testCaseInsensitiveAlias()
    {
        $this->assertSortedSame(['ie 11'], $this->list->query('Last 01 Explorer Version')->toArray());
    }
}
