<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Illuminate\Support\Collection;

class DirectDriver extends RegexpDriver
{

    public function getRegexp()
    {
        return '/^(\w+)\s+(tp|[\d\.]+)$/i';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        list($name, $version) = $arguments;

        if (strtolower($version) == 'tp') {
            $version = 'TP';
        }

        $browser = $this->processVersion($list, $name, $version);
        return Collection::make([$browser]);
    }

    public function processVersion(Browserslist $list, $name, $version)
    {
        if ($versionAlias = $list->normalizeVersion($name, $version)) {
            $version = $versionAlias;
        } else {
            // Add a .0 if there is none and remove a .0 is there is
            if (strpos($version, '.') === false) {
                $versionAlias = $version . '.0';
            } elseif (substr($version, -2) == '.0') {
                $versionAlias = substr($version, 0, -2);
            }

            if ($versionAlias = $list->normalizeVersion($name, $versionAlias)) {
                $version = $versionAlias;
            } else {
                throw new \InvalidArgumentException("Unknown version {$version} of {$name}.");
            }
        }

        $data = $list->getDataByBrowser($name);
        return $data['name'] . ' ' . $version;
    }

}
