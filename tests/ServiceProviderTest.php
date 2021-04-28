<?php

namespace Tests;

use DarkGhostHunter\Laradate\LaradateServiceProvider;
use DarkGhostHunter\Laralerts\Bag;
use DarkGhostHunter\Laralerts\Facades\Alert;
use DarkGhostHunter\Laralerts\Http\Middleware\StorePersistentAlertsInSession;
use DarkGhostHunter\Laralerts\LaralertsServiceProvider;
use DarkGhostHunter\Laralerts\View\Component\LaralertsComponent;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\View\Compilers\BladeCompiler;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    use RegistersPackage;

    public function test_registers_package(): void
    {
        static::assertArrayHasKey(LaradateServiceProvider::class, $this->app->getLoadedProviders());
    }
}
