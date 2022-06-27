<?php

namespace LisaFehr\Gallery\Providers;

use Illuminate\Support\ServiceProvider;
use LisaFehr\Gallery\Console\Commands\GenerateImages;
use LisaFehr\Gallery\Console\Commands\InstallCommand;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/lisa-fehr/gallery'),
        ], 'public');

        $filesystem = require(__DIR__.'/../../src/config/filesystems.php');
        foreach ($filesystem as $key => $fs) {
            $this->app['config']["filesystems.disks.{$key}"] = $fs;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateImages::class,
                InstallCommand::class,
            ]);
        }
    }
}
