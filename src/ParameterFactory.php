<?php

namespace Risan\LaraDataTable;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ParameterFactory
{
    public $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function makeFromRequest(array $sorts = [], array $filters = [], array $configOverride = []): Parameter
    {
        $config = array_merge($this->config, $configOverride);

        $params = Validator::make(
            Request::all(), 
            $this->rules($filters, $config)
        )->validate();

        $options = $this->paramsToOptions($params, $config);
        $options['sorts'] = $this->paramsToSortsOption($params, $sorts, $config);
        $options['filters'] = $this->paramsToFiltersOption($params, $filters);

        return new Parameter($options, $this->config['per_page_default']);
    }

    private function rules(array $filters, array $config): array
    {
        $rules = [];
        $rules[$config['page_param']] = 'int|min:1';
        $rules[$config['per_page_param']] = 'int|min:1|max:'.$config['per_page_max'];
        $rules[$config['search_param']] = 'nullable|string';

        if ($config['multiple_sorts']) {
            $rules[$config['sorts_param']] = 'array';
            $rules[$config['sorts_param'].'.*'] = 'array';
            $rules[$config['sorts_param'].'.*.'.$config['sort_by_param']] = [
                'string',
                'required_with:'.$config['sorts_param'].'.*.'.$config['sort_direction_param'],
            ];
            $rules[$config['sorts_param'].'.*.'.$config['sort_direction_param']] = [
                'string',
                'in:'.Sort::ASC.','.Sort::DESC,
            ];
        } else {
            $rules[$config['sort_by_param']] = [
                'string',
                'required_with:'.$config['sort_direction_param'],
            ];
            $rules[$config['sort_direction_param']] = [
                'string',
                'in:'.Sort::ASC.','.Sort::DESC,
            ];
        }

        foreach ($filters as $filter => $filterConfig) {
            if (is_string($filterConfig)) {
                $rules[$filter] = $filterConfig;
            } else if (is_array($filterConfig)) {
                $rules[$filter] = Arr::has($filterConfig, 'rule') ? $filterConfig['rule'] : $filterConfig;
            } else {
                throw new Exception("DataTable filters[{$filter}] must be a string or an array.");
            }
        }

        return $rules;
    }

    private function paramsToOptions(array $params, array $config): array
    {
        $keys = ['page', 'per_page', 'search'];
        $options = [];

        foreach ($keys as $key) {
            if (Arr::has($params, $config["{$key}_param"])) {
                Arr::set($options, $key, Arr::get($params, $config["{$key}_param"]));
            }
        }

        return $options;
    }

    private function paramsToSortsOption(array $params, array $sorts, array $config): array
    {
        $option = [];

        if (!$config['multiple_sorts']) {
            if (Arr::has($params, $config['sort_by_param'])) {
                $by = $params[$config['sort_by_param']];

                if (Arr::has($sorts, $by)) {
                    $by = $sorts[$by];
                }

                $option[0]['by'] = $by;

                if (Arr::has($params, $config['sort_direction_param'])) {
                    $option[0]['direction'] = $params[$config['sort_direction_param']];
                }
            }

            return $option;
        }

        if (Arr::has($params, $config['sorts_param'])) {
            $sortItems = $params[$config['sorts_param']];

            foreach ($sortItems as $sort) {
                if (Arr::has($sort, $config['sort_by_param'])) {
                    $by = $sort[$config['sort_by_param']];

                    if (Arr::has($sorts, $by)) {
                        $by = $sorts[$by];
                    }

                    if (Arr::has($sort, $config['sort_direction_param'])) {
                        $option[] = [
                            'by' => $by,
                            'direction' => $sort[$config['sort_direction_param']],
                        ];
                    } else {
                        $option[] = ['by' => $by];
                    }
                }
            }
        }

        return $option;
    }

    private function paramsToFiltersOption(array $params, array $filters): array
    {
        $option = [];

        foreach ($filters as $input => $config) {
            if (!Arr::has($params, $input)) {
                continue;
            }

            $by = $input;

            if (is_array($config) && Arr::has($config, 'field')) {
                $by = Arr::get($config, 'field');
            }

            $option[] = [
                'by' => $by,
                'value' => Arr::get($params, $input),
            ];
        }

        return $option;
    }
}