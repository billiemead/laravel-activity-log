<?php

namespace Billiemead\LaravelActivityLog;

use Billiemead\LaravelActivityLog\Console\UserActivityDelete;
use Billiemead\LaravelActivityLog\Console\UserActivityInstall;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/activity-log.php';
    const ROUTE_PATH = __DIR__ . '/../routes';
    const VIEW_PATH = __DIR__ . '/../views';
    const ASSET_PATH = __DIR__ . '/../assets';
    const MIGRATION_PATH = __DIR__ . '/../migrations';


    private function publish()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('activity-log.php')
        ], 'config');

        $this->publishes([
            self::MIGRATION_PATH => database_path('migrations')
        ], 'migrations');
    }

    public function boot()
    {
        $this->publish();

        $this->loadRoutesFrom(self::ROUTE_PATH . '/web.php');
        $this->loadViewsFrom(self::VIEW_PATH, 'LaravelActivityLog');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'activity-log'
        );

        $this->app->register(EventServiceProvider::class);
        $this->commands([UserActivityInstall::class, UserActivityDelete::class]);
    }

}
