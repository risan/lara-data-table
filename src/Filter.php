<?php

namespace Risan\LaraDataTable;

class Filter
{
    public $by;
    public $value;

    public function __construct(string $by, $value)
    {
        $this->by = $by;
        $this->value = $value;
    }
}