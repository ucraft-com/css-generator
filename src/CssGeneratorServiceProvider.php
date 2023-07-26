<?php

declare(strict_types=1);

namespace CssGenerator;

use Illuminate\Support\ServiceProvider;

class CssGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind(CssGenerator::class, function () {
            return new CssGenerator();
        });
    }
}
