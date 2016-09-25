<?php

namespace Buttress\Browserslist;

use Buttress\Browserslist\Query\CustomStats;
use Buttress\Browserslist\Query\DirectDriver;
use Buttress\BrowsersList\Query\Driver;
use Buttress\Browserslist\Query\FirefoxESR;
use Buttress\Browserslist\Query\GlobalStats;
use Buttress\Browserslist\Query\LastByBrowser;
use Buttress\Browserslist\Query\LastVersions;
use Buttress\Browserslist\Query\OperaMini;
use Buttress\Browserslist\Query\Range;
use Buttress\Browserslist\Query\Versions;
use Illuminate\Support\Collection;

class Browserslist
{
    /** @var Driver[] */
    protected $queryDrivers;

    /** @var \Illuminate\Support\Collection */
    protected $aliases;

    /** @var Collection  */
    protected $versionAliases;

    /** @var Collection */
    protected $usage;

    /** @var \Illuminate\Support\Collection  */
    protected $browsers;

    /** @var \Illuminate\Support\Collection */
    protected $data;

    /** @var array|string */
    protected $defaultQuery;

    public function __construct($data)
    {
        if (!$data instanceof Collection) {
            $data = new Collection($data);
        }

        $this->defaultQuery = '> 1%, last 2 versions, Firefox ESR';
        $this->data = $data;
        $this->versionAliases = new Collection();

        $this->aliases = new Collection([
            'fx' => 'firefox',
            'ff' => 'firefox',
            'ios' => 'ios_saf',
            'explorer' => 'ie',
            'blackberry' => 'bb',
            'explorermobile' => 'ie_mob',
            'operamini' => 'op_mini',
            'operamobile' => 'op_mob',
            'chromeandroid' => 'and_chr',
            'firefoxandroid' => 'and_ff',
            'ucandroid' => 'and_uc'
        ]);

        $this->browsers = new Collection(['safari', 'opera', 'ios_saf', 'ie_mob', 'ie', 'edge', 'firefox', 'chrome']);
        $this->queryDrivers = [
            new LastVersions(),
            new LastByBrowser(),
            new OperaMini(),
            new CustomStats(),
            new GlobalStats(),
            new FirefoxESR(),
            new Versions(),
            new Range(),
            new DirectDriver()
        ];
    }

    public function coverage(array $browsers, $country = 'global')
    {
        $list = $this;

        if (strtolower($country) !== 'global') {
            throw new \InvalidArgumentException('Country specific coverage is not supported.');
        }

        return Collection::make($browsers)->reduce(function ($total, $browser) use ($list) {
            if (!$usage = array_get($list->getUsage()->get("global"), $browser)) {
                $usage = array_get($list->getUsage()->get("global"), preg_replace('/ [\d.]+$/', ' 0', $browser));
            }

            return $total + floatval($usage);
        }, 0);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAliases()
    {
        return $this->aliases;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBrowsers()
    {
        return $this->browsers;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return $this->data;
    }

    public function getDataByBrowser($name)
    {
        $name = strtolower($name);
        $data = $this->getData();
        $aliases = $this->getAliases();

        if ($aliases->has($name)) {
            $name = $aliases[$name];
        }

        $browser = $data->first(function ($browser) use ($name) {
            return $browser['name'] === $name;
        });

        if ($browser) {
            return $browser;
        }
    }

    public function normalizeVersion($browserName, $version)
    {
        $data = $this->getDataByBrowser($browserName);

        if (isset($data['versions']) && in_array($version, $data['versions'])) {
            // Do this instead of returning the version because the matched version might not be exact
            foreach ($data['versions'] as $realVersion) {
                if ($realVersion == $version) {
                    return $realVersion;
                }
            }
        } else {
            if ($aliases = $this->getVersionAliases()->get($data['name'])) {
                if (isset($aliases[$version])) {
                    return $aliases[$version];
                }
            }
        }
    }

    /**
     * @return array|string
     */
    public function getDefaultQuery()
    {
        return $this->defaultQuery;
    }

    /**
     * @param array|string $query
     */
    public function setDefaultQuery($query)
    {
        $this->defaultQuery = $query;
    }

    /**
     * @return Collection
     */
    public function getVersionAliases()
    {
        return $this->versionAliases;
    }

    /**
     * @param Collection $versionAliases
     */
    public function setVersionAliases(Collection $versionAliases)
    {
        $this->versionAliases = $versionAliases;
    }

    /**
     * @return Collection
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @param Collection $usage
     */
    public function setUsage(Collection $usage)
    {
        $this->usage = $usage;
    }

    /**
     * Run a query against the Browserlist data
     * @param $query
     * @return \Illuminate\Support\Collection
     * @throws \InvalidArgumentException If a query passed is not recognized
     */
    public function query($query = null)
    {
        if ($query === null) {
            $query = $this->defaultQuery;
        }

        $result = new Collection();
        foreach ($this->generateQueries($query) as $subQuery) {
            if (strlen($subQuery) > 4 && substr($subQuery, 0, 4) == 'not ') {
                $without = $this->handleQuery(substr($subQuery, 4));
                $result = $result->reject(function ($item) use ($without) {
                    return !!$without->contains($item);
                });
            } else {
                $result = $result->merge($this->handleQuery($subQuery));
            }
        }

        return $result->unique()->sort($this->queryResultSort())->values();
    }

    private function handleQuery($query)
    {
        foreach ($this->queryDrivers as $driver) {
            if ($driver->handlesQuery($query)) {
                return $driver->query($query, $this);
            }
        }

        throw new \InvalidArgumentException("Unknown browser query `{$query}`");
    }

    public function __invoke(...$arguments)
    {
        return $this->query(...$arguments);
    }

    private function generateQueries($query)
    {
        if (is_string($query)) {
            $query = explode(',', $query);
        }

        foreach ((array) $query as $queryString) {
            if ($trimmed = trim($queryString)) {
                yield $trimmed;
            }
        }
    }

    private function queryResultSort()
    {
        return function ($a, $b) {
            $result = strnatcmp($a, $b);

            $lettersOnlyA = preg_replace('/[^a-z]/i', '', $a);
            $lettersOnlyB = preg_replace('/[^a-z]/i', '', $b);

            // If the browsers are the same
            if ($lettersOnlyA === $lettersOnlyB) {
                return -1 * $result;
            }

            return $result;
        };
    }
}
