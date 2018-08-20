<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\BrowsersList;
use Tightenco\Collect\Support\Collection;

class CustomStats extends RegexpDriver
{
    public function processQuery(BrowsersList $list, ...$arguments)
    {
        list($percent) = $arguments;

        $usage = $list->getUsage()->get('custom');

        if (!$usage) {
            throw new \RuntimeException('Custom usage statistics was not provided');
        }

        $result = new Collection();
        foreach ($usage as $version => $percentage) {
            if ($percentage >= $percent) {
                $result->push($version);
            }
        }

        return $result;
    }

    public function getRegexp()
    {
        return '/^>\s*(\d*\.?\d+)%\s+in\s+my\s+stats$/';
    }
}
