<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\BrowsersList;
use Illuminate\Support\Collection;

class OperaMini extends RegexpDriver
{

    public function processQuery(BrowsersList $list, ...$arguments)
    {
        return new Collection('op_mini all');
    }

    public function getRegexp()
    {
        return '/(operamini|op_mini)\s+all/i';
    }
}
