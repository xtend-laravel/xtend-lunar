<?php

namespace Xtend\Extensions\Lunar\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xtend:lunar-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the extended Lunar resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing XtendLunar Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'xtend-lunar-provider']);

        $this->registerXtendLunarServiceProvider();

        $this->info('XtendLunar scaffolding installed successfully.');
    }

    /**
     * Register the XtendLunar service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerXtendLunarServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\XtendLunarServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\XtendLunarServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/XtendLunarServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/XtendLunarServiceProvider.php'))
        ));
    }
}
