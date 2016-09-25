<?php

namespace Buttress\Browserslist;

use Buttress\Browserslist\Browser\VersionNormalizer;
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
    /** @var Driver[]|string[] */
    protected $queryDrivers;

    /** @var \Illuminate\Support\Collection */
    protected $aliases;

    /** @var Collection */
    protected $versionAliases;

    /** @var Collection */
    protected $usage;

    /** @var \Illuminate\Support\Collection */
    protected $browsers;

    /** @var \Illuminate\Support\Collection */
    protected $data;

    /** @var array|string */
    protected $defaultQuery;

    /** @var VersionNormalizer */
    protected $normalizer;

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
            LastVersions::class,
            LastByBrowser::class,
            OperaMini::class,
            CustomStats::class,
            GlobalStats::class,
            FirefoxESR::class,
            Versions::class,
            Range::class,
            DirectDriver::class
        ];
    }

    /**
     * Get the usage of a browser or set of browsers
     * @param array $browsers
     * @param string $country
     * @return mixed
     */
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
     * Get the data for a particular browser name
     * @param $name
     * @return array|null
     */
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

    /**
     * Take a version string and return the best possible match based on browsername and available versions
     * @param $browserName
     * @param $version
     * @return null|string
     */
    public function normalizeVersion($browserName, $version)
    {
        $normalizer = $this->getVersionNormalizer();
        return $normalizer->normalizeVersion($browserName, $version);
    }

    /**
     * Get the version normalizer object
     * @return \Buttress\Browserslist\Browser\VersionNormalizer
     */
    protected function getVersionNormalizer()
    {
        if (!$this->normalizer) {
            $this->normalizer = new VersionNormalizer($this);
        }

        return $this->normalizer;
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
            if ($rejectQuery = $this->shouldReject($subQuery)) {
                $result = $this->rejectQuery($result, $rejectQuery);
            } else {
                $result = $result->merge($this->handleQuery($subQuery));
            }
        }

        return $result->unique()->sort($this->queryResultSort())->values();
    }

    /**
     * Generator method for retrieving cleaned query strings
     * @param $query
     * @return \Generator
     */
    private function generateQueries($query)
    {
        if (is_string($query)) {
            $query = explode(',', $query);
        }

        foreach ((array)$query as $queryString) {
            if ($trimmed = trim($queryString)) {
                yield $trimmed;
            }
        }
    }

    /**
     * @param $query
     * @return bool|string
     */
    private function shouldReject($query)
    {
        if (strlen($query) > 4 && substr($query, 0, 4) == 'not ') {
            return substr($query, 4);
        }

        return false;
    }

    /**
     * Takes a collection and removes the results of the passed query
     * @param \Illuminate\Support\Collection $result
     * @param $query
     * @return Collection
     */
    private function rejectQuery(Collection $result, $query)
    {
        $without = $this->handleQuery($query);

        return $result->reject(function ($item) use ($without) {
            return !!$without->contains($item);
        });
    }

    /**
     * Internal query handling method, this delegates to drivers
     * @param $query
     * @return \Illuminate\Support\Collection
     */
    private function handleQuery($query)
    {
        foreach ($this->queryDriverGenerator() as $driver) {
            if ($driver->handlesQuery($query)) {
                return $driver->query($query, $this);
            }
        }

        throw new \InvalidArgumentException("Unknown browser query `{$query}`");
    }

    /**
     * Inflates drivers as they are iterated over
     * @return \Generator
     */
    private function queryDriverGenerator()
    {
        foreach ($this->queryDrivers as $key => $driver) {
            if (is_string($driver) && class_exists($driver)) {
                $driver = new $driver;
                $this->queryDrivers[$key] = $driver;
                yield $driver;
            } else {
                yield $driver;
            }
        }
    }

    /**
     * Result sort closure
     *
     * Sorts in natural, then reverse natural order.
     *
     * Example sort order:
     * a 10
     * a 9
     * b 100
     * b 60
     * z 50
     * z 1
     *
     * @return \Closure
     */
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

    /**
     * Magic method for running a query by invoking the class
     * @param array ...$arguments
     * @return \Illuminate\Support\Collection
     */
    public function __invoke(...$arguments)
    {
        return $this->query(...$arguments);
    }
}
