<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Managers;

use Illuminate\Support\Manager;
use Xtend\Extensions\Lunar\Admin\Forms\Traits\CanResolveFromContainer;

class ImageManager extends Manager
{
    use CanResolveFromContainer;

    public function getDefaultDriver(): string
    {
        return config('lunar.images.driver', 's3');
    }
}
