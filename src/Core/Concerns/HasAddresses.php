<?php

namespace Xtend\Extensions\Lunar\Core\Concerns;

use Illuminate\Support\Collection;
use Lunar\Models\Address;

trait HasAddresses
{
    /**
     * Return the address relationships.
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function addresses(): ?Collection
    {
        return ! $this->isSameAddress()
            ? collect($this->billingAddress)->merge($this->shippingAddress)
            : collect($this->billingAddress);
    }

    /**
     * Return the shipping address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Return the billing address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Compare the shipping and billing address to see if they are the same.
     *
     * @return bool
     */
    protected function isSameAddress(): bool
    {
        return $this->billingAddress?->first()->id === $this->shippingAddress?->first()->id;
    }
}
