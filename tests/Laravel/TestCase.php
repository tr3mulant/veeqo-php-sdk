<?php

namespace Tests\Laravel;

use TremulantTech\Veeqo\VeeqoServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [VeeqoServiceProvider::class];
    }
}
