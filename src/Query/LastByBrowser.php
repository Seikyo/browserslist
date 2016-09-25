<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\BrowsersList;
use Illuminate\Support\Collection;

class LastByBrowser extends RegexpDriver
{

    public function processQuery(BrowsersList $list, ...$arguments)
    {
        list($count, $name) = $arguments;

        $data = $list->getDataByBrowser($name);
        $versions = Collection::make($data['released']);

        return $versions->slice(-1 * intval($count))->map(function ($version) use ($data) {
            return implode(' ', [$data['name'], $version]);
        });
    }

    public function getRegexp()
    {
        return '/^last\s+(\d+)\s+(\w+)\s+versions?$/i';
    }
}
