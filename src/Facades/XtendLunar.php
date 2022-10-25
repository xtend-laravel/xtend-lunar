<?php

namespace Xtend\Extensions\Lunar\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class XtendLunar
 *
 * @method static void withRegister()
 * @method static void withBoot()
 * @method static void bootWithModels(Collection $models)
 *
 * @see \Xtend\Extensions\Lunar\src\XtendLunar
 */
class XtendLunar extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Xtend\Extensions\Lunar\src\XtendLunar::class;
    }
}
