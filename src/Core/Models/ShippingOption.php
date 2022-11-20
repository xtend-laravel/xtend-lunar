<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;

class ShippingOption extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = [];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(ShippingLocation::class);
    }
}
