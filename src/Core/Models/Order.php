<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Notifications\Notifiable;
use Lunar\Base\Casts\DiscountBreakdown;
use Lunar\Base\Casts\Price;
use Lunar\Base\Casts\TaxBreakdown;

/**
 * Class Order
 *
 * @property \Lunar\Models\Cart $cart
 */
class Order extends \Lunar\Models\Order
{
    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'tax_breakdown' => TaxBreakdown::class,
        'discount_breakdown' => DiscountBreakdown::class,
        'meta' => 'object',
        'placed_at' => 'datetime',
        'sub_total' => Price::class,
        'discount_total' => Price::class,
        'tax_total' => Price::class,
        'total' => Price::class,
        'shipping_total' => Price::class,
        'legacy_data' => AsCollection::class,
    ];

    protected static function booted(): void
    {
        // static::created(function (self $order) {
        //     //$order->notify(static::orderNotification($order));
        // });
    }

    /**
     * Return the cart relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo(\Lunar\Models\Cart::class);
    }
}
