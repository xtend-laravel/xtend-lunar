<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Carts;

use Livewire\Component;
use Lunar\Models\Order;

class CartsIndex extends Component
{
    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.carts.index', [
            'ordersMonthly' => $this->getOrdersMonthly(),
            'averageOrderTotal' => $this->getAverageOrderTotal(),
            'dispatchedOrders' => $this->getDispatchedOrders(),
            'cancelledOrders' => $this->getCancelledOrders(),
        ])->layout('adminhub::layouts.base');
    }

    private function getOrdersMonthly(): int
    {
        return Order::whereMonth('created_at', now()->month)->count();
    }

    private function getAverageOrderTotal(): int
    {
        return Order::whereMonth('created_at', now()->month)->avg('total') ?? 0;
    }

    private function getDispatchedOrders(): int
    {
        return Order::whereMonth('created_at', now()->month)->where('status', 'dispatched')->count();
    }

    private function getCancelledOrders(): int
    {
        return Order::where('status', 'cancelled')->count();
    }
}
