<?php

namespace Xtend\Extensions\Lunar\Providers;

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Lunar\Hub\AdminHubServiceProvider as AdminHubBaseServiceProvider;
use Lunar\Hub\Menu\OrderActionsMenu;
use Xtend\Extensions\Lunar\Admin\Listeners\SetStaffAuthMiddlewareListener;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderAddress;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderDiscount;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Product\Options\OptionEdit;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Product\Options\OptionsIndex;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Product\Options\OptionValueEdit;
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

    /**
     * Register the components used in the settings area.
     *
     * @return void
     */
    protected function registerSettingsComponents()
    {
        parent::registerSettingsComponents();

        // Product Options
        Livewire::component('hub.components.settings.product.options.index', OptionsIndex::class);
        Livewire::component('hub.components.settings.product.option-edit', OptionEdit::class);
        Livewire::component('hub.components.settings.product.option-value-edit', OptionValueEdit::class);
    }

    protected function registerOrderComponents(): void
    {
        Livewire::component('hub.components.orders.discount', OrderDiscount::class);
        Livewire::component('hub.components.orders.show', OrderShow::class);
        Livewire::component('hub.components.orders.address', OrderAddress::class);
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
}
