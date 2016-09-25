<?php

namespace Buttress\Browserslist\Tests;

use Buttress\Browserslist\Browserslist;
use Buttress\Browserslist\BrowserslistFactory;
use Buttress\Browserslist\Parser\DataParser;

trait ListSetup
{

    /** @var Browserslist */
    protected $list;

    public function setUp()
    {
        if ($this instanceof DataProvider) {
            $this->list = $this->populateList(new Browserslist($this->getData()));
        } else {
            $this->list = $this->createCachedList();
        }
    }

    private function createCachedList()
    {
        if (TestCase::$dataCache) {
            // Cache is already populated
            $list = new Browserslist(TestCase::$dataCache);
            $list->setUsage(TestCase::$usageCache);
            $list->setVersionAliases(TestCase::$aliasCache);

            return $this->populateList($list);
        } else {
            $list = (new BrowserslistFactory())->createWithData();
            TestCase::$dataCache = $list->getData();
            TestCase::$usageCache = $list->getUsage();
            TestCase::$aliasCache = $list->getVersionAliases();

            return $this->populateList($list);
        }
    }

    protected function populateList(Browserslist $list)
    {
        return $list;
    }

    public function tearDown()
    {
        $this->list = null;
    }
}
