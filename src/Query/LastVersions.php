<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;
use Tightenco\Collect\Support\Collection;

class LastVersions extends RegexpDriver
{
    public function processQuery(BrowsersList $list, ...$arguments)
    {
        list($versions) = $arguments;
        $selected = new Collection();

        $list->getBrowsers()->each(function ($browserName) use ($list, $versions, &$selected) {
            $browserData = $list->getDataByBrowser($browserName);

            if (!$browserData) {
                return;
            }

            $selectedVersions = Collection::make($browserData['released'])
                ->slice(-1 * $versions)
                ->map(function ($version) use ($browserData) {
                    return implode(' ', [$browserData['name'], $version]);
                });

            $selected = $selected->merge($selectedVersions);
        });

        return $selected;
    }

    /**
     * @return string
     */
    public function getRegexp()
    {
        return '/^last\s+(\d+)\s+versions?$/i';
    }
}
