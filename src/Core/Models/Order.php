<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Lunar\Base\Casts\Price;
use Lunar\Base\Casts\TaxBreakdown;
use XtendLunar\Features\NotifyTimeline\Base\Notification;
use XtendLunar\Features\NotifyTimeline\Concerns\HasModelNotification;

/**
 * Class Order
 *
 * @property \Lunar\Models\Cart $cart
 */
class Order extends \Lunar\Models\Order
{
    use HasModelNotification;
    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'tax_breakdown' => TaxBreakdown::class,
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
        static::created(function (self $order) {
            $order->notify($this->orderNotification($order));
        });
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
