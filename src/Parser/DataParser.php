<?php

namespace Buttress\Browserslist\Parser;

class DataParser
{
    public function parse($json, $config = [])
    {
        $result = new Result();
        $agents = $json['agents'];
        foreach ($agents as $name => $data) {
            $this->parseData($name, $data, $result);
            $this->parseUsage($name, $data, $result);
            $this->parseAliases($name, $data, $result);
        }

        if (isset($config['stats'])) {
            foreach ($config['stats'] as $name => $data) {
                $this->parseCustomStats($name, $data, $result);
            }
        }

        return $result;
    }

    private function normalize($list)
    {
        return array_filter($list, function ($item) {
            return !!$item;
        });
    }

    public function apply($container, $name, $data)
    {
        foreach ($data as $key => $value) {
            $container[$name . ' ' . $key] = $value;
        }

        return $container;
    }

    /**
     * @param $name
     * @param $data
     * @param $result
     */
    private function parseAliases($name, $data, Result $result)
    {
        $aliases = [];
        foreach ($data['versions'] as $version) {
            if ($version && strpos($version, '-') !== false) {
                $interval = explode('-', $version);

                foreach ($interval as $node) {
                    $aliases[$node] = $version;
                }
            }
        }
        $result->aliases->put($name, $aliases);
    }

    /**
     * @param $name
     * @param $data
     * @param $result
     */
    private function parseUsage($name, $data, Result $result)
    {
        $global = $result->usage->get('global', []);
        $result->usage['global'] = $this->apply($global, $name, $data['usage_global']);
    }

    /**
     * @param $name
     * @param $data
     * @param $result
     */
    private function parseData($name, $data, Result $result)
    {
        $result->data->put($name, [
            "name" => $name,
            "versions" => $this->normalize($data['versions']),
            "released" => $this->normalize($data['versions'])
        ]);
    }

    private function parseCustomStats($name, $data, Result $result)
    {
        $stats = $result->usage->get('custom', []);
        $result->usage['custom'] = $this->apply($stats, $name, $data);
    }
}
