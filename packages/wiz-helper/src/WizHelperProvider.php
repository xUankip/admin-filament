<?php

namespace Wiz\Helper;

use Closure;
use Illuminate\Support\ServiceProvider;
use Wiz\Helper\Consoles\CacheClear;

class WizHelperProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function boot(): void
    {
        //$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->commands([
            CacheClear::class,
        ]);
    }
}
