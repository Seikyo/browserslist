<?php

namespace Buttress\Browserslist\Query;

use Buttress\Browserslist\Browserslist;

class GlobalStats extends RegexpDriver
{
    public function getRegexp()
    {
        return '/^>\s*(\d*\.?\d+)%$/';
    }

    public function processQuery(Browserslist $list, ...$arguments)
    {
        list($popularity) = $arguments;
        $popularity = floatval($popularity);

        $result = collect();

        foreach ($list->getUsage()->get('global', []) as $version => $usage) {
            if ($usage > $popularity) {
                $result->push($version);
            }
        }

        return $result;
    }
}
