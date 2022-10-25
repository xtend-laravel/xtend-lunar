<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Notifications\Notifiable;
use Lunar\Base\Casts\Price;
use Lunar\Base\Casts\TaxBreakdown;
use Xtend\Extensions\Filament\Notifications\Notification;

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
            $order->notify(
                Notification::make()
                    ->success()
                    ->system()
                    ->title('New order placed successfully!')
                    ->body("**{$order->customer->fullName}** ordered **{$order->lines->count()}** products.")
                    ->actions([
                        Action::make('view')
                          ->button()
                          ->url(route('hub.orders.show', ['customer' => $order])),
                    ])
                    ->toDatabase()
            );
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
