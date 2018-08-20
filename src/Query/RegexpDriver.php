<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Tightenco\Collect\Support\Collection;

/**
 * A regular expression powered driver
 * @package Buttress\BrowsersList\Query
 */
abstract class RegexpDriver implements Driver
{
    /**
     * The regular expression to test against
     * @return string
     */
    abstract public function getRegexp();

    /**
     * @param \Buttress\Browserslist\Browserslist $list
     * @param array $arguments
     * @return Collection
     */
    abstract public function processQuery(Browserslist $list, ...$arguments);

    /**
     * @inheritdoc
     */
    public function query($string, BrowsersList $list)
    {
        $matches = [];
        preg_match($this->getRegexp(), $string, $matches);

        array_shift($matches);

        return $this->processQuery($list, ...$matches);
    }

    /**
     * @inheritdoc
     */
    public function handlesQuery($string)
    {
        return preg_match($this->getRegexp(), $string);
    }
}
