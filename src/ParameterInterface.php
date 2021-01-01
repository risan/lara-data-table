<?php

namespace Risan\LaraDataTable;

interface ParameterInterface
{
    public function getPage(): int;

    public function getPerPage(): int;

    public function getSearch(): string;
}