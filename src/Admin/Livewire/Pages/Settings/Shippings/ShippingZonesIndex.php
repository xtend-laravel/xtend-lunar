<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Pages\Settings\Shippings;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShippingZonesIndex extends Component
{
    public function render(): View
    {
        return view('adminhub::livewire.pages.settings.shippings.shipping-zones.index')
            ->layout('adminhub::layouts.settings', [
                'title' => __('Shipping Zones'),
                'menu' => 'settings',
            ]);
    }
}
