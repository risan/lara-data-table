<?php

namespace Risan\LaraDataTable;

use Illuminate\Support\Facades\App;

class DataTableBuilder
{
    public $globalConfig;
    public $config = [];
    public $query;
    public $parameter;

    public function __construct(array $globalConfig)
    {
        $this->globalConfig = $globalConfig;
    }

    public function config(array $config)
    {
        $this->config = $config;

        return $this;
    }

    public function from($query)
    {
        $this->query = $query;

        return $this;
    }

    public function get()
    {
        $parameter = $this->parameter
            ?? ParameterFactory::makeFromRequest(App::make('request'), array_merge($this->globalConfig, $this->config));

        return new DataTable($this->query, $parameter);
    }
}