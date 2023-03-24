<?php

namespace Xtend\Extensions\Lunar\Providers;

use Lunar\LunarServiceProvider as LunarBaseServiceProvider;

class LunarServiceProvider extends LunarBaseServiceProvider
{
    public function register(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $path = __DIR__.'/../Config/lunar/'.$config.'.php';
            $this->mergeConfigFrom($path, 'lunar.'.$config);
        });

        parent::register();
    }
}
