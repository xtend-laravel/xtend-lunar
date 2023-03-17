<?php

namespace Xtend\Extensions\Lunar\Providers;

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Lunar\Hub\AdminHubServiceProvider as AdminHubBaseServiceProvider;
use Lunar\Hub\Menu\OrderActionsMenu;
use Xtend\Extensions\Lunar\Admin\Listeners\SetStaffAuthMiddlewareListener;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Customers\CustomerShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderAddress;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderDiscount;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductOptions\OptionEdit;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductOptions\OptionValueEdit;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\ProductCreate;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\ProductShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\ProductOptions\ProductOptionsIndex;
use XtendLunar\Features\SidebarMenu\Menu\SettingsMenu;
use XtendLunar\Features\SidebarMenu\Menu\SidebarMenu;

class AdminHubServiceProvider extends AdminHubBaseServiceProvider
{
    protected $root = __DIR__.'/../Config/lunar-hub';

    public function register(): void
    {
        $this->registerWithConfig();
        $this->loadRoutesFrom(__DIR__.'/../Admin/Routes/web.php');
    }

    protected function registerWithConfig(): void
    {
        collect($this->configFiles)->each(function ($config) {
            config([
                "lunar-hub.$config" => array_merge(
                    config("lunar-hub.$config"),
                    include_once("{$this->root}/$config.php"),
                ),
            ]);
        });
    }

    protected function registerProductComponents()
    {
        //Livewire::component('hub.components.products.show', ProductShow::class);
        //Livewire::component('hub.components.products.create', ProductCreate::class);
    }

    protected function registerOrderComponents(): void
    {
        Livewire::component('hub.components.orders.discount', OrderDiscount::class);
        Livewire::component('hub.components.orders.show', OrderShow::class);
        Livewire::component('hub.components.orders.address', OrderAddress::class);
    }

    protected function registerSettingsComponents(): void
    {
        // @todo Move to ShippingProviders feature package
        // Livewire::component('hub.components.settings.shippings.tables.list-shipping-zones', ListShippingZones::class);
        // Livewire::component('hub.components.settings.shippings.tables.list-shipping-locations', ListShippingLocations::class);
        // Livewire::component('hub.components.settings.shippings.tables.list-shipping-options', ListShippingOptions::class);
    }

    public function boot()
    {
        parent::boot();

        Event::listen(
            RouteMatched::class,
            [SetStaffAuthMiddlewareListener::class, 'handle']
        );
    }

    protected function registerMenuBuilder(): void
    {
        Event::listen(LocaleUpdated::class, function () {
            if (Feature::active('sidebar-menu')) {
                SidebarMenu::make();
                SettingsMenu::make();
            }
            OrderActionsMenu::make();
        });
    }

    protected function registerConverters()
    {
        // @todo Look to change library or submit PR to fix this issue with catalistc
        // $this->registerExchangers();
        // $this->registerConverter();
    }
}
