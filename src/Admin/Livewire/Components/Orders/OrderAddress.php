<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Lunar\Models\Address;
use Lunar\Models\Order;

class OrderAddress extends Component
{
    public bool $showAddressEditConfirmation = false;

    public ?Order $order;

    public Collection $addressesOptions;

    public string $type;

    public string $heading;

    public bool $hidden;

    public ?Address $address;

    public ?string $message;

    public int $addressId = 0;

    public function mount()
    {
        $this->addressId = $this->order->{$this->type.'_address_id'} ?? 0;
        $this->address = Address::find($this->addressId);
    }

    public function updatedAddressId(): void
    {
        $this->address = Address::find($this->addressId);
    }

    public function updateAddress(): void
    {
        if (! $this->addressId) {
            $this->showAddressEditConfirmation = false;

            return;
        }

        $address = Address::find($this->addressId);
        $orderAddressArr = collect($address->toArray())->merge([
            'type' => $this->type,
        ])->toArray();

        match ($this->type) {
            'shipping' => $this->updateShippingAddress($orderAddressArr),
            'billing' => $this->updateBillingAddress($orderAddressArr),
        };

        $this->order->{$this->type.'_address_id'} = $address->id;
        $this->order->update();

        $this->showAddressEditConfirmation = false;
    }

    protected function updateShippingAddress(array $orderAddressArr): void
    {
        $this->order->shippingAddress()->delete();
        $this->order->shippingAddress()->create($orderAddressArr);
    }

    protected function updateBillingAddress(array $orderAddressArr): void
    {
        $this->order->billingAddress()->delete();
        $this->order->billingAddress()->create($orderAddressArr);
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.orders.address');
    }
}
