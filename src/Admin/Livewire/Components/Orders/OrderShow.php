<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders;

use Lunar\Base\Addressable;
use Lunar\Hub\Http\Livewire\Components\Orders\OrderShow as LunarOrderShow;
use Lunar\Models\Address;
use Lunar\Models\OrderAddress;

class OrderShow extends LunarOrderShow
{
    /**
     * Whether to show the discount panel.
     *
     * @var bool
     */
    public bool $showDiscount = false;
    
    /**
     * The instance of the shipping address.
     *
     * @var \Lunar\Models\OrderAddress
     */
    public ?OrderAddress $shippingAddress = null;

    /**
     * The instance of the shipping address.
     *
     * @var \Lunar\Models\OrderAddress
     */
    public ?OrderAddress $billingAddress = null;

    /**
     * Whether to show the address edit screen.
     *
     * @var bool
     */
    public bool $showShippingAddressEdit = false;

    /**
     * Whether to show the billing address edit.
     *
     * @var bool
     */
    public bool $showBillingAddressEdit = false;

    /**
     * @var int
     */
    public int $shippingAddressId = 0;

    /**
     * @var int
     */
    public int $billingAddressId = 0;

    public function mount()
    {
        $this->shippingAddress = $this->order->shippingAddress ?: new OrderAddress();

        $this->billingAddress = $this->order->billingAddress ?: new OrderAddress();
    }

    /**
     * Handler when shipping edit toggle is updated.
     *
     * @return void
     */
    public function updatedShowShippingAddressEdit()
    {
        $this->shippingAddress = $this->shippingAddress->refresh();
    }

    /**
     * Handler when billing address is changed.
     *
     * @return void
     */
    public function updatedBillingAddressId()
    {
        if (! $this->billingAddressId) {
            return;
        }

        $address = Address::findOrFail($this->billingAddressId);
        $this->order->billingAddress()->delete();
        $this->billingAddress = $this->order->billingAddress()->create(
            collect($address->toArray())->merge([
                'address_id' => $address->id,
                'type' => 'billing',
            ])->toArray()
        );
    }

    /**
     * Handler when shipping address is changed.
     *
     * @return void
     */
    public function updatedShippingAddressId()
    {
        if (! $this->shippingAddressId) {
            return;
        }

        $address = Address::findOrFail($this->shippingAddressId);
        $this->order->shippingAddress()->delete();
        $this->shippingAddress = $this->order->shippingAddress()->create(
            collect($address->toArray())->merge([
                'address_id' => $address->id,
                'type' => 'shipping',
            ])->toArray()
        );
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAddressesOptionsProperty()
    {
        return $this->order->customer->addresses->mapWithKeys(function (Addressable $address) {
            return [$address->id => $address->formatted];
        });
    }

    /**
     * Handle when a discount is successful.
     *
     * @return void
     */
    public function discountSuccess()
    {
        $this->showDiscount = false;
    }
}
