<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Pages\Settings\Shippings;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShippingOptionsIndex extends Component
{
    public function render(): View
    {
        return view('adminhub::livewire.pages.settings.shippings.shipping-options.index')
            ->layout('adminhub::layouts.settings', [
                'title' => __('Shipping Options'),
                'menu' => 'settings',
            ]);
    }
}
