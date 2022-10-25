<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Pages\Carts;

use Livewire\Component;

class CartsIndex extends Component
{
    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.pages.carts.index')
            ->layout('adminhub::layouts.app', [
                'title' => 'Orders',
            ]);
    }
}
