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

        $this->app->singleton(DefaultParameter::class, function ($app) {
            return new DefaultParameter($app['config']['datatable.per_page_default']);
        });

        $this->app->singleton(ParameterFactory::class, function ($app) {
            return new ParameterFactory($app['config']['datatable']);
        });

        $this->app->singleton('laraDataTable.factory', function ($app) {
            return new DataTableFactory($app[ParameterFactory::class]);
        });
    }
}
