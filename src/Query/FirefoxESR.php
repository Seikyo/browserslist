<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Tightenco\Collect\Support\Collection;

class FirefoxESR extends RegexpDriver
{
    public function getRegexp()
    {
        return '/^(firefox|ff|fx)\s+esr$/i';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        return Collection::make(['firefox 45']);
    }
}
