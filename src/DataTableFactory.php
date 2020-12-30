<?php

namespace Risan\LaraDataTable;

use Illuminate\Database\Query\Builder;

class DataTableFactory
{
    public $parameterFactory;

    public function __construct(ParameterFactory $parameterFactory)
    {
        $this->parameterFactory = $parameterFactory;
    }

    public function from(Builder $query, array $sorts = [], array $filters = [], array $config = []): DataTable
    {
        return new DataTable(
            $query, 
            $this->parameterFactory->makeFromRequest($sorts, $filters, $config)
        );
    }
}