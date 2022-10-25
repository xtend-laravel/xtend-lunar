<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Pages\Carts;

use Livewire\Component;
use Lunar\Models\Order;

class CartShow extends Component
{
    /**
     * The Product we are currently editing.
     *
     * @var \Lunar\Models\Product
     */
    public Order $order;

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.pages.orders.show')
            ->layout('adminhub::layouts.app', [
                'title' => __('adminhub::orders.show.title', ['id' => $this->order->id]),
            ]);
    }
}
