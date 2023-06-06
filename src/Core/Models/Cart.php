<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Lunar\Pipelines\Cart\Calculate;

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
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function calculate(): \Lunar\Models\Cart
    {
        $cart = app(Pipeline::class)
        ->send($this)
        ->through(
            config('lunar.cart.pipelines.cart', [
                Calculate::class,
            ])
        )->thenReturn();

        return $cart;
    }
}
