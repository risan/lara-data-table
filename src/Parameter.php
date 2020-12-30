<?php

namespace Risan\LaraDataTable;

use Exception;
use Illuminate\Support\Arr;

class Parameter implements ParameterInterface
{
    protected $page;
    protected $perPage;
    protected $search;
    protected $sorts;
    protected $filters;

    public function __construct(array $params = [], int $defaultPerPage = 25)
    {
        $this->page = (int) Arr::get($params, 'page', 1);

        if ($this->page <= 0) {
            throw new Exception('DataTable page option must be larger than 0.');
        }

        $this->perPage = (int) Arr::get($params, 'per_page', $defaultPerPage);

        if ($this->perPage <= 0) {
            throw new Exception('DataTable per_page option must be larger than 0.');
        }

        $this->search = trim((string) Arr::get($params, 'search', ''));

        $sorts = Arr::get($params, 'sorts', []);

        if (!is_array($sorts)) {
            throw new Exception('DataTable sorts option must be an array.');
        }

        $this->sorts = SortCollection::createFromArray($sorts);

        $filters = Arr::get($params, 'filters', []);

        if (!is_array($filters)) {
            throw new Exception('DataTable filters option must be an array.');
        }

        $this->filters = FilterCollection::createFromArray($filters);
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

    public function getSorts(): SortCollection
    {
        return $this->sorts;
    }

    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }
}