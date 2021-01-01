<?php

namespace Risan\LaraDataTable;

use Illuminate\Support\ServiceProvider;

class LaraDataTableServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/datatable.php', 'datatable'
        );

        $this->app->singleton(DataTableBuilder::class, function ($app) {
            return new DataTableBuilder($app->config['datatable']);
        });
    }
}
