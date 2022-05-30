<?php

namespace LisaFehr\Gallery\Providers;

use Illuminate\Support\ServiceProvider;
use LisaFehr\Gallery\Console\Commands\GenerateImages;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/resources/js/components' =>
                resource_path('assets/lisa-fehr/gallery/components'
                )], 'vue-components');
        $this->publishes([
            __DIR__ . '/resources/js/views' =>
                resource_path('assets/lisa-fehr/gallery/views'
                )], 'vue-views');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/lisa-fehr/gallery'),
        ], 'public');
        // php artisan vendor:publish --tag=public --force

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateImages::class,
            ]);
        }
    }
}
