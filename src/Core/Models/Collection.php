<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Lunar\Base\Casts\AsAttributeData;

class Collection extends \Lunar\Models\Collection
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
    ];
}
