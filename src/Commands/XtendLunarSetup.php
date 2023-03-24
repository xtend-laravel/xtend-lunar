<?php

namespace Xtend\Extensions\Lunar\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\DB;

class XtendLunarSetup extends Command
{
    protected $signature = 'xtend:lunar-setup';

    protected $description = 'Setup XtendLunar Extension';

    public function __construct(
        protected Filesystem $filesystem,
        protected Composer $composer)
    {
        parent::__construct();
    }

    public function handle(PackageManifest $packageManifest): int
    {
        $this->packageManifest = $packageManifest;
        $this->migrate();
        $this->seed();

        return self::SUCCESS;
    }

    protected function migrate(): void
    {
        DB::transaction(function () {
            $this->call('migrate', [
                '--path' => __DIR__.'/../../database/migrations',
            ]);
        });
    }

    protected function seed(): void
    {
        $this->call('db:seed', [
            '--class' => 'Xtend\\Extensions\\Lunar\\Database\\Seeders\\XtendLunarSeeder',
        ]);
    }
}
