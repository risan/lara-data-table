<?php

namespace Risan\LaraDataTable;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SortCollection extends Collection
{
    private function __construct(array $items)
    {
        parent::__construct($items);
    }
    
    public static function createFromArray(array $sorts): Collection
    {
        $items = array_map(function ($sort, $idx) {
            if (!is_array($sort)) {
                throw new Exception("DataTable sorts[{$idx}] option must be an array.");
            }

            if (!Arr::has($sort, 'by')) {
                throw new Exception("DataTable sorts[{$idx}]['by'] option is missing.");
            }

            $by = Arr::get($sort, 'by');

            if (!is_string($by)) {
                throw new Exception("DataTable sorts[{$idx}]['by'] option must be a string.");
            }

            $by = trim($by);

            if (empty($by)) {
                throw new Exception("DataTable sorts[{$idx}]['by'] option must not be empty.");
            }

            $direction = strtolower(trim((string) Arr::get($sort, 'direction', Sort::ASC)));

            if (!in_array($direction, [Sort::ASC, Sort::DESC])) {
                throw new Exception("DataTable sorts[{$idx}]['direction'] option must be set to '".Sort::ASC."' or '".Sort::DESC."'.");
            }

            return new Sort($by, $direction);
        }, $sorts, array_keys($sorts));
        
        return new static($items);
    }
}