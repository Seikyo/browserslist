<?php

namespace Buttress\BrowsersList\Query;

use Buttress\Browserslist\BrowsersList;
use Illuminate\Support\Collection;

/**
 * Query Driver
 * Handles calls to $list->query()
 * @package Buttress\BrowsersList
 */
interface Driver
{

    /**
     * @param $string
     * @param \Buttress\Browserslist\BrowsersList $list
     * @return Collection
     */
    public function query($string, BrowsersList $list);

    /**
     * Does this driver handle the given query
     * @param $string
     * @return bool
     */
    public function handlesQuery($string);
}
