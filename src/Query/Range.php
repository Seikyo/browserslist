<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Illuminate\Support\Collection;

class Range extends RegexpDriver
{

    public function getRegexp()
    {
        return '/^(\w+)\s+([\d\.]+)\s*-\s*([\d\.]+)$/i';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        list ($name, $from, $to) = $arguments;

        if (!$data = $list->getDataByBrowser($name)) {
            throw new \InvalidArgumentException("Unknown browser {$name}");
        }
        $from = floatval($list->normalizeVersion($name, $from));
        $to = floatval($list->normalizeVersion($name, $to));

        $releases = new Collection($data['released']);

        return $releases
            ->filter(function ($item) use ($from, $to) {
                $float = floatval($item);
                return $float >= $from && $float <= $to;
            })
            ->map(function ($version) use ($name) {
                return "{$name} {$version}";
            });
    }
}
