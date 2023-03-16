<?php

namespace Xtend\Extensions\Lunar;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use Illuminate\Support\Collection;

class XtendLunarServiceProvider extends ExtendsProvider
{
    protected Collection $features;

    /**
     * XtendLunarServiceProvider service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerWithProvider();
    }

    protected function registerWithProvider(): void
    {
        $this->callAfterResolving('blade.compiler', function () {
            if (!$this->app->providerIsLoaded('App\\Providers\\Xtend\\XtendLunarServiceProvider')) {
                $this->app->register(XtendLunarProvider::class);
            }
        });
    }

    /**
     * XtendLunarServiceProvider service provider
     *
     * @return void
     */
    public function boot(): void
    {
        $this->offerPublishing();
        $this->registerCommands();
    }

    /**
     * Set up the resource publishing groups for XtendLaravel.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/XtendLunarServiceProvider.stub' => app_path('Providers/XtendLunarServiceProvider.php'),
            ], 'xtend-lunar-provider');
        }
    }

    /**
     * Register the XtendLunar Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
            ]);
        }
    }
}
