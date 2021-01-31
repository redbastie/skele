<?php

namespace Redbastie\Skele\Providers;

use Illuminate\Support\ServiceProvider;

class SkeleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Redbastie\Skele\Commands\AuthCommand::class,
                \Redbastie\Skele\Commands\CrudCommand::class,
                \Redbastie\Skele\Commands\ComponentCommand::class,
                \Redbastie\Skele\Commands\InstallCommand::class,
                \Redbastie\Skele\Commands\ListingCommand::class,
                \Redbastie\Skele\Commands\MigrateCommand::class,
                \Redbastie\Skele\Commands\ModelCommand::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
