<?php

namespace DarkGhostHunter\Laradate;

use Illuminate\Contracts\Routing\BindingRegistrar;
use Illuminate\Support\DateFactory;
use Illuminate\Support\ServiceProvider;

class LaradateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(DateFactoryBind::class, static function($app) : DateFactoryBind {
            return new DateFactoryBind($app[DateFactory::class]);
        });

        $this->app->afterResolving('router', static function (BindingRegistrar $router): void {
            $router->bind('datetime', 'DarkGhostHunter\Laradate\DateFactoryBind@datetime');
            $router->bind('date', 'DarkGhostHunter\Laradate\DateFactoryBind@date');
        });
    }
}
