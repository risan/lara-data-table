<?php

namespace Risan\LaraDataTable;

use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Support\Arrayable;

class DataTable implements Arrayable
{
    public $query;
    public $parameter;

    public function __construct(Builder $query, ParameterInterface $parameter)
    {
        $this->query = $query;
        $this->parameter = $parameter;
    }

    public function toArray()
    {
        return [
            'page' => $this->parameter->getPage(),
            'per_page' => $this->parameter->getPerPage(),
            'search' => $this->parameter->getSearch(),
            'sorts' => $this->parameter->getSorts(),
            'filters' => $this->parameter->getFilters(),
            'total' => $this->query->count(),
            'data' => $this->query->take($this->parameter->getPerPage())->get(),
        ];
    }
}