<?php

namespace Risan\LaraDataTable;

class DefaultParameter implements ParameterInterface
{
    private $perPage;
    
    public function __construct($perPage = 25)
    {
        $this->perPage = $perPage;
    }

    public function getPage(): int
    {
        return 1;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getSearch(): string
    {
        return '';
    }

    public function getSorts(): SortCollection
    {
        return SortCollection::createFromArray([]);
    }

    public function getFilters(): FilterCollection
    {
        return FilterCollection::createFromArray([]);
    }
}