<?php

namespace Tests;

use DarkGhostHunter\Laradate\LaradateServiceProvider;

trait RegistersPackage
{
    protected function getPackageProviders($app): array
    {
        return [LaradateServiceProvider::class];
    }
}
