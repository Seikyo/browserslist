<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Tightenco\Collect\Support\Collection;

class Versions extends RegexpDriver
{
    public function getRegexp()
    {
        return '/^(\w+)\s*(>=?|<=?)\s*([\d\.]+)$/';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        list($name, $sign, $version) = $arguments;
        $data = $list->getDataByBrowser($name);

        if ($versionAlias = $list->getVersionNormalizer()->normalizeVersion($name, $version)) {
            $version = $versionAlias;
        }

        $version = floatval($version);
        $releases = Collection::make($data['released']);

        switch ($sign) {
            case '>':
                $releases = $releases->filter(function ($release) use ($version) {
                    return $release > $version;
                });
                break;
            case '>=':
                $releases = $releases->filter(function ($release) use ($version) {
                    return floatval($release) >= $version;
                });
                break;
            case '<':
                $releases = $releases->filter(function ($release) use ($version) {
                    return floatval($release) < $version;
                });
                break;
            case '<=':
                $releases = $releases->filter(function ($release) use ($version) {
                    return floatval($release) <= $version;
                });
                break;
        }

        return $releases->map(function ($version) use ($name) {
            return "{$name} {$version}";
        });
    }
}
