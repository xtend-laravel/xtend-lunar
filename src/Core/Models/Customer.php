<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\LogsActivity;
use Lunar\Models\Cart;
use Lunar\Models\Language;
use XtendLunar\Features\NotifyTimeline\Concerns\HasModelNotification;

class Customer extends \Lunar\Models\Customer
{
    use LogsActivity;
    use Notifiable;
    use HasModelNotification;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
        'dob' => 'date:Y-m-d',
        'meta' => 'object',
    ];

    protected static function booted(): void
    {
        static::created(function (self $customer) {
            $customer->notify(static::makeNotification(
                type: 'success',
                title: 'New customer created successfully!',
                body: "System detected new customer registration named **{$customer->fullName}**.",
                route: route('hub.customers.show', ['customer' => $customer]),
            ));
        });
    }

    /**
     * Return the language relationship.
     *
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Return the cart's relationship.
     *
     * @return BelongsTo
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
