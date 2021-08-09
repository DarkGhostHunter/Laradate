<?php

namespace DarkGhostHunter\Laradate;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\BindingRegistrar;
use Illuminate\Contracts\Routing\Registrar as RegistrarContract;
use Illuminate\Routing\Router;
use Illuminate\Support\DateFactory;
use Illuminate\Support\ServiceProvider;

/**
 * @internal
 */
class LaradateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(DateRouteBind::class, static function($app) : DateRouteBind {
            return new DateRouteBind($app[DateFactory::class]);
        });

        $this->app->afterResolving('router', static function (Router $router, Application $app): void {
            $router->bind('date', [$app->make(DateRouteBind::class), 'date']);

            $router->aliasMiddleware('date', Http\Middleware\ContainDate::class);
        });
    }
}
