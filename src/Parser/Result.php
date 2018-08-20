<?php

namespace Buttress\Browserslist\Parser;

use Tightenco\Collect\Support\Collection;

class Result
{
    /**
     * @var Collection
     */
    public $data;

    /**
     * @var Collection
     */
    public $usage;

    /**
     * @var Collection
     */
    public $aliases;

    public function __construct()
    {
        $this->data = new Collection();
        $this->usage = new Collection();
        $this->aliases = new Collection();
    }
}
