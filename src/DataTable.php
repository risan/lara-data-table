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
            'total' => $this->query->count(),
            'data' => $this->data(),
        ];
    }

    private function data()
    {
        $query = $this->query->clone();

        if ($this->shouldApplySearch()) {
            $this->applySearch($query);
        }

        return $query->skip(($this->parameter->getPage() - 1) * $this->parameter->getPerPage())
            ->take($this->parameter->getPerPage())
            ->get();
    }

    private function shouldApplySearch(): bool
    {
        if (!$this->parameter->getSearch()) {
            return false;
        }

        return count($this->searchableColumns()) > 0;
    }

    private function searchableColumns(): array
    {
        if (!is_array($this->query->columns)) {
            return [];
        }

        return array_filter($this->query->columns, function ($column) {
            return $column != '*';
        });
    }

    private function applySearch(Builder $query)
    {
        $query->where(function ($query) {
            foreach ($this->searchableColumns() as $idx => $column) {
                $query->where($column, 'LIKE', '%'.$this->parameter->getSearch().'%', $idx == 0 ? 'and' : 'or');
            }
        });
    }
}