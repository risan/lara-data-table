<?php

namespace Risan\LaraDataTable;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FilterCollection extends Collection
{
    private function __construct(array $items)
    {
        parent::__construct($items);
    }
    
    public static function createFromArray(array $filters): Collection
    {
        $items = array_map(function ($filter, $idx) {
            if (!is_array($filter)) {
                throw new Exception("DataTable filters[{$idx}] option must be an array.");
            }

            if (!Arr::has($filter, 'by')) {
                throw new Exception("DataTable filters[{$idx}]['by'] option is missing.");
            }

            $by = Arr::get($filter, 'by');

            if (!is_string($by)) {
                throw new Exception("DataTable filters[{$idx}]['by'] option must be a string.");
            }

            $by = trim($by);

            if (empty($by)) {
                throw new Exception("DataTable filters[{$idx}]['by'] option must not be empty.");
            }

            if (!Arr::has($filter, 'value')) {
                throw new Exception("DataTable filters[{$idx}]['value'] option is missing.");
            }

            $value = Arr::get($filter, 'value');

            if (is_string($value)) {
                $value = trim($value);
            }

            return new Filter($by, $value);
        }, $filters, array_keys($filters));
        
        return new static($items);
    }
}