<?php

namespace Xtend\Extensions\Lunar\Providers;

use Lunar\LunarServiceProvider as LunarBaseServiceProvider;

class LunarServiceProvider extends LunarBaseServiceProvider
{
    public function register(): void
    {
        //$this->registerWithConfig();

        parent::register();
    }

    protected function registerWithConfig(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $path = __DIR__.'/../Config/lunar/'.$config.'.php';
            $this->mergeConfigFrom($path, 'lunar.'.$config);
        });
    }
}
