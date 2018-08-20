<?php

namespace Buttress\Browserslist\Browser;

use Buttress\Browserslist\Browserslist;

class VersionNormalizer
{
    /** @var \Buttress\Browserslist\Browserslist */
    protected $list;

    public function __construct(Browserslist $list)
    {
        $this->list = $list;
    }

    /**
     * Take a version string and return the normalized version.
     * This method uses the browserlist's main data and version alias data
     * to resolve the proper version from an alias.
     *
     * @param $browserName
     * @param $browserVersion
     * @return null|string
     */
    public function normalizeVersion($browserName, $browserVersion)
    {
        $data = $this->list->getDataByBrowser($browserName);

        if ($version = $this->getVersionFromData($data, $browserVersion)) {
            return $version;
        }
        $aliases = $this->list->getVersionAliases()->get($data['name'], []);

        return $this->getVersionFromAliases($aliases, $browserVersion);
    }

    /**
     * @param $data
     * @param $version
     * @return string|null
     */
    private function getVersionFromData($data, $version)
    {
        $versions = $data['versions'] ?? [];
        if (in_array($version, $versions)) {
            return $this->resolveRealVersion($versions, $version);
        }
    }

    /**
     * @param array $versions
     * @param $version
     * @return string|null
     */
    private function resolveRealVersion(array $versions, $version)
    {
        foreach ($versions as $realVersion) {
            if ($version == $realVersion) {
                return $realVersion;
            }
        }
    }

    /**
     * @param array $aliases
     * @param $version
     * @return string
     */
    private function getVersionFromAliases(array $aliases, $version)
    {
        if (isset($aliases[$version])) {
            return $aliases[$version];
        }
    }
}
