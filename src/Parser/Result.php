<?php

namespace Buttress\Browserslist\Parser;

use Illuminate\Support\Collection;

class Result
{

    /**
     * @var \Illuminate\Support\Collection
     */
    public $data;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $usage;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $aliases;

    public function __construct()
    {
        $this->data = new Collection();
        $this->usage = new Collection();
        $this->aliases = new Collection();
    }
}
