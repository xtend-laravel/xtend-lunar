<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;

class ShippingLocation extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = [];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ShippingLocation::class, 'parent_id');
    }
}
