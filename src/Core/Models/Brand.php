<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;

class Brand extends \Lunar\Models\Brand
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'legacy_data' => AsCollection::class,
    ];
}
