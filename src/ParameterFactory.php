<?php

namespace Risan\LaraDataTable;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ParameterFactory
{
    public static function makeFromRequest(Request $request, array $c): Parameter
    {
        $data = Validator::make($request->all(), [
            $c['page_param'] => ['int', 'min:1'],
            $c['per_page_param'] => ['int', 'min:1', "max:{$c['per_page_max']}"],
            $c['search_param'] => 'string',
        ])->validate();

        $page = (int) Arr::get($data, $c['page_param'], 1);
        $perPage = (int) Arr::get($data, $c['per_page_param'], $c['per_page_default']);
        $search = Arr::get($data, $c['search_param'], '');

        return new Parameter($page, $perPage, $search);
    }
}