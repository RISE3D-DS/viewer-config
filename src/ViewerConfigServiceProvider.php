<?php

namespace Rise3d\ViewerConfig;

use Illuminate\Support\ServiceProvider;

class ViewerConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('viewer-config', function () {
            return new ViewerConfig();
        });
    }
}
