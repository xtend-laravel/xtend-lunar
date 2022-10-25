<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;

class ProductOptionValue extends \Lunar\Models\ProductOptionValue
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'name' => AsCollection::class,
        'legacy_data' => AsCollection::class,
    ];
}
