<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Lunar\Base\Traits\HasTranslations;

class CustomerGroup extends \Lunar\Models\CustomerGroup
{
    use HasTranslations;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'legacy_data' => AsCollection::class,
        'name' => AsCollection::class,
    ];
}
