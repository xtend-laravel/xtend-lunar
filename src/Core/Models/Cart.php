<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * Class Cart
 *
 * @see \Lunar\Models\Cart
 */
class Cart extends \Lunar\Models\Cart
{
    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'meta' => 'object',
        'legacy_data' => AsCollection::class,
    ];

    /**
     * Return the customer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
