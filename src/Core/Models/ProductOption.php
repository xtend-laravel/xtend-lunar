<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;

class ProductOption extends \Lunar\Models\ProductOption
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'name' => AsCollection::class,
        'legacy_data' => AsCollection::class,
    ];
}
