<?php

namespace Xtend\Extensions\Lunar;

use CodeLabX\XtendLaravel\Base\XtendPackageProvider;

class XtendLunarServiceProvider extends XtendPackageProvider
{
    protected static string $packageToXtend = 'lunarphp/lunar';

    /**
     * XtendLunarServiceProvider register
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xtend-lunar.php', 'lunar');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerWithProvider();
    }

    protected function registerWithProvider(): void
    {
        $this->callAfterResolving('blade.compiler', function () {
            $appProvider = 'App\\Providers\\XtendLunarServiceProvider';
            $this->app->register(
                provider: !class_exists($appProvider)
                    ? XtendLunarProvider::class
                    : $appProvider,
            );
        });
    }

    /**
     * XtendLunarServiceProvider boot
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/XtendLunarServiceProvider.stub' => app_path('Providers/XtendLunarServiceProvider.php'),
            ], 'xtend-lunar-provider');
            $this->publishes([
                __DIR__.'/../config/xtend-lunar.php' => config_path('xtend/lunar.php'),
            ], 'xtend-lunar-config');
        }
    }
}
