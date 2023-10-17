<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders;

use Lunar\Hub\Http\Livewire\Components\Orders\OrderShow as LunarOrderShow;

class OrderShow extends LunarOrderShow
{
    /**
     * {@inheritDoc}
     */
    protected function getListeners()
    {
        return array_merge(parent::getListeners(), [
            'notifyShipmentWarning',
            'notifyShipmentSuccess',
        ]);
    }

    public function notifyShipmentWarning()
    {
        $this->dispatchBrowserEvent('notifyShipmentWarning');
    }

    public function notifyShipmentSuccess()
    {
        $this->dispatchBrowserEvent('notifyShipmentSuccess');
    }
}
