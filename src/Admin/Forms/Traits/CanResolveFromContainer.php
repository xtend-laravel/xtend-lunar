<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Traits;

trait CanResolveFromContainer
{
    /**
     * Resolves a field from the container.
     *
     * @param  string  $name
     * @return mixed
     */
    public static function make(string $name): static
    {
        return resolve(static::class, ['name' => $name]);
    }
}
