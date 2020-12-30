<?php

namespace Risan\LaraDataTable;

class Sort
{
    const ASC = 'asc';
    const DESC = 'desc';
    
    public $by;
    public $direction;

    public function __construct(string $by, string $direction = 'asc')
    {
        $this->by = $by;
        $this->direction = $direction;
    }
}