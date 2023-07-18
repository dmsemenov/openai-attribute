<?php

namespace Dmsemenov\OpenaiAttribute;

use Dmsemenov\OpenaiAttribute\Console\Commands\OpenAiGenerate;
use Illuminate\Support\ServiceProvider;

class OpenaiAttributeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dmsemenov');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'dmsemenov');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/openai-attribute.php', 'openai-attribute');

        // Register the service the package provides.
        $this->app->singleton('openai-attribute', function ($app) {
            return new OpenaiAttribute;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['openai-attribute'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/openai-attribute.php' => config_path('openai-attribute.php'),
        ], 'openai-attribute.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/dmsemenov'),
        ], 'openai-attribute.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/dmsemenov'),
        ], 'openai-attribute.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/dmsemenov'),
        ], 'openai-attribute.views');*/

        // Registering package commands.
        $this->commands([
            OpenAiGenerate::class
        ]);
    }
}
