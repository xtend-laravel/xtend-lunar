<?php

namespace Xtend\Extensions\Lunar\Admin\Menu;

use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\SettingsMenu as LunarSettingsMenu;

class SettingsMenu extends LunarSettingsMenu
{
    protected function makeTopLevel(): static
    {
        parent::makeTopLevel();

        $slot = Menu::slot('settings');

        $storeSection = $slot->section('store')->name(
            'Store'
        );

        $storeSection->addItem(function ($item) {
            $item->name('Shipping')
                 ->handle('hub.shippings')
                 ->route('hub.shippings.shipping-zones.index')
//                 ->gate('settings:manage-shippings')
                 ->icon('truck');
        });

        return $this;
    }
}
