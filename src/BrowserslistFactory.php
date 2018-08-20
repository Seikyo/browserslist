<?php

namespace Buttress\Browserslist;

class BrowserslistFactory
{
    /**
     * @param array $config
     * @param string $dataFile
     * @return \Buttress\Browserslist\Browserslist
     */
    public function createWithData($config = [], $dataFile = __DIR__ . '/../data/caniuse.json')
    {
        $config = $this->handleStats($config);

        $object = $this->getJson($dataFile);
        $result = (new Parser\DataParser())->parse($object, $config);
        $list = new Browserslist($result->data);
        $list->setVersionAliases($result->aliases);
        $list->setUsage($result->usage);

        return $list;
    }

    private function getJson($string)
    {
        // Sorry mom, we're gonna have to load it into memory.
        $data = file_get_contents($string);
        $object = json_decode($data, true);
        unset($data);

        return $object;
    }

    private function handleStats($config)
    {
        if (($stats = $config['stats'] ?? null) && is_string($stats)) {
            $stats = $this->getJson($stats);

            if (isset($stats['dataByBrowser'])) {
                $stats = $stats['dataByBrowser'];
            }

            $config['stats'] = $stats;
        }

        return $config;
    }
}
