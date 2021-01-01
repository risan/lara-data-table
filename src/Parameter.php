<?php

namespace Risan\LaraDataTable;

class Parameter implements ParameterInterface
{
    protected $page;
    protected $perPage;
    protected $search;

    public function __construct(int $page = 1, int $perPage = 25, string $search = '')
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->search = $search;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getSearch(): string
    {
        return $this->search;
    }
}