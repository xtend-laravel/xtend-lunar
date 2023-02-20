<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Models\Product;

class Collection extends \Lunar\Models\Collection
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
    ];

    public function publishedProducts()
    {
        return $this->products()->where('status', 'published');
    }
}
