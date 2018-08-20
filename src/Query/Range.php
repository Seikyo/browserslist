<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Tightenco\Collect\Support\Collection;

class Range extends RegexpDriver
{
    public function getRegexp()
    {
        return '/^(\w+)\s+([\d\.]+)\s*-\s*([\d\.]+)$/i';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        list($name, $from, $to) = $arguments;

        if (!$data = $list->getDataByBrowser($name)) {
            throw new \InvalidArgumentException("Unknown browser {$name}");
        }
        $normalizer = $list->getVersionNormalizer();
        $from = (float)$normalizer->normalizeVersion($name, $from);
        $to = (float)$normalizer->normalizeVersion($name, $to);

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
