<?php

namespace Larabra\Generator;

use Illuminate\Support\ServiceProvider;
use Larabra\Generator\Commands\API\APIControllerGeneratorCommand;
use Larabra\Generator\Commands\API\APIGeneratorCommand;
use Larabra\Generator\Commands\API\APIRequestsGeneratorCommand;
use Larabra\Generator\Commands\API\TestsGeneratorCommand;
use Larabra\Generator\Commands\APIScaffoldGeneratorCommand;
use Larabra\Generator\Commands\Common\MigrationGeneratorCommand;
use Larabra\Generator\Commands\Common\ModelGeneratorCommand;
use Larabra\Generator\Commands\Common\RepositoryGeneratorCommand;
use Larabra\Generator\Commands\Publish\GeneratorPublishCommand;
use Larabra\Generator\Commands\Publish\LayoutPublishCommand;
use Larabra\Generator\Commands\Publish\PublishTemplateCommand;
use Larabra\Generator\Commands\Publish\PublishUserCommand;
use Larabra\Generator\Commands\RollbackGeneratorCommand;
use Larabra\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use Larabra\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use Larabra\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use Larabra\Generator\Commands\Scaffold\ViewsGeneratorCommand;

class LarabraGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__.'/../config/laravel_generator.php';
            $this->publishes([
                $configPath => config_path('larabra/laravel_generator.php'),
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel_generator.php', 'larabra.laravel_generator');

        $this->app->singleton('larabra.publish', function ($app) {
            return new GeneratorPublishCommand();
        });

        $this->app->singleton('larabra.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('larabra.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('larabra.publish.layout', function ($app) {
            return new LayoutPublishCommand();
        });

        $this->app->singleton('larabra.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('larabra.api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand();
        });

        $this->app->singleton('larabra.migration', function ($app) {
            return new MigrationGeneratorCommand();
        });

        $this->app->singleton('larabra.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('larabra.repository', function ($app) {
            return new RepositoryGeneratorCommand();
        });

        $this->app->singleton('larabra.api.controller', function ($app) {
            return new APIControllerGeneratorCommand();
        });

        $this->app->singleton('larabra.api.requests', function ($app) {
            return new APIRequestsGeneratorCommand();
        });

        $this->app->singleton('larabra.api.tests', function ($app) {
            return new TestsGeneratorCommand();
        });

        $this->app->singleton('larabra.scaffold.controller', function ($app) {
            return new ControllerGeneratorCommand();
        });

        $this->app->singleton('larabra.scaffold.requests', function ($app) {
            return new RequestsGeneratorCommand();
        });

        $this->app->singleton('larabra.scaffold.views', function ($app) {
            return new ViewsGeneratorCommand();
        });

        $this->app->singleton('larabra.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->app->singleton('larabra.publish.user', function ($app) {
            return new PublishUserCommand();
        });

        $this->commands([
            'larabra.publish',
            'larabra.api',
            'larabra.scaffold',
            'larabra.api_scaffold',
            'larabra.publish.layout',
            'larabra.publish.templates',
            'larabra.migration',
            'larabra.model',
            'larabra.repository',
            'larabra.api.controller',
            'larabra.api.requests',
            'larabra.api.tests',
            'larabra.scaffold.controller',
            'larabra.scaffold.requests',
            'larabra.scaffold.views',
            'larabra.rollback',
            'larabra.publish.user',
        ]);
    }
}
