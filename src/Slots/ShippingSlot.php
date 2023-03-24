<?php

namespace Xtend\Extensions\Lunar\Slots;

use Livewire\Component;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;

class ShippingSlot extends Component implements AbstractSlot
{
    use HubSlot;

    protected $rules = [
        'slotModel.carrier_name' => 'required',
        'slotModel.carrier_price' => 'required',
    ];

    public static function getName()
    {
        return 'hub.orders.slots.shipping-slot';
    }

    public function getSlotHandle()
    {
        return 'shipping-slot';
    }

    public function getSlotInitialValue()
    {
        return [
        ];
    }

    public function getSlotPosition()
    {
        return 'top';
    }

    public function getSlotTitle()
    {
        return 'Shipping';
    }

    public function submit()
    {
        $this->validate();
    }

    public function render()
    {
        return view('adminhub::livewire.components.shipping-slot');
    }
}
