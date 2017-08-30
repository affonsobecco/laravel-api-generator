<?php

namespace Becco\Generator;

use Illuminate\Support\ServiceProvider;
use Becco\Generator\Commands\APIGeneratorCommand;
use Becco\Generator\Commands\PublisherCommand;
use Becco\Generator\Commands\ScaffoldAPIGeneratorCommand;
use Becco\Generator\Commands\ScaffoldGeneratorCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../../../config/generator.php';

        $this->publishes([
            $configPath => config_path('generator.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mitul.generator.publish', function ($app) {
            return new PublisherCommand();
        });

        $this->app->singleton('mitul.generator.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('mitul.generator.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('mitul.generator.scaffold_api', function ($app) {
            return new ScaffoldAPIGeneratorCommand();
        });

        $this->commands([
            'mitul.generator.publish',
            'mitul.generator.api',
            'mitul.generator.scaffold',
            'mitul.generator.scaffold_api',
        ]);
    }
}
