<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Lunar\Base\Casts\AsAttributeData;

class ProductVariant extends \Lunar\Models\ProductVariant
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'requires_shipping' => 'bool',
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
    ];
}
