<?php

namespace Tests;

use DarkGhostHunter\Laradate\LaradateServiceProvider;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    use RegistersPackage;

    public function test_registers_package(): void
    {
        static::assertArrayHasKey(LaradateServiceProvider::class, $this->app->getLoadedProviders());
    }
}
