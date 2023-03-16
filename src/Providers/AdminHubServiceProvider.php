<?php

namespace Xtend\Extensions\Lunar\Providers;

use CodeLabX\XtendLaravel\Services\Translation\FileLoader;
use CodeLabX\XtendLaravel\Services\Translation\TranslationServiceProvider;
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
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Forms\ChannelForm;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Forms\CustomerDetailForm;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderAddress;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderDiscount;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\OrderShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductOptions\OptionEdit;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductOptions\OptionValueEdit;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\ProductCreate;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\ProductShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\ProfileForm;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Shippings\Tables\ListShippingLocations;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Shippings\Tables\ListShippingOptions;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Shippings\Tables\ListShippingZones;
use Xtend\Extensions\Lunar\Admin\Livewire\Components\SwitchLanguage;
use Xtend\Extensions\Lunar\Admin\Livewire\Dashboard;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\ProductOptions\ProductOptionsIndex;
use Xtend\Extensions\Lunar\Admin\Livewire\Sidebar;
use Xtend\Extensions\Lunar\Admin\Menu\SettingsMenu;
use Xtend\Extensions\Lunar\Admin\Menu\SidebarMenu;

class AdminHubServiceProvider extends AdminHubBaseServiceProvider
{
    public function register(): void
    {
        parent::register();
        $this->registerWithConfig();

        $this->loadRoutesFrom(__DIR__.'/../Admin/Routes/web.php');
    }

    protected function registerWithConfig(): void
    {
        // @todo improve make sure to keep in sync with base class configs
        collect($this->configFiles)->each(function ($config) {
            $path = __DIR__.'/../Config/lunar-hub/'.$config.'.php';
            $this->mergeConfigFrom($path, 'lunar-hub.'.$config);
        });
    }

    protected function registerLivewireComponents(): void
    {
        parent::registerLivewireComponents();

        Livewire::component('sidebar', Sidebar::class);
        Livewire::component('dashboard', Dashboard::class);
        Livewire::component('hub.components.switch-language', SwitchLanguage::class);

        // Livewire Form Components
        $this->registerFormComponents();

        // Livewire Table Components
        $this->registerLivewireTableComponents();

        // Blade Components
        Blade::componentNamespace('Xtend\\Extensions\\Lunar\\Admin\\Views\\Components', 'xtend:hub');
    }

    protected function registerProductComponents()
    {
        parent::registerProductComponents();

        Livewire::component('hub.components.products.show', ProductShow::class);
        Livewire::component('hub.components.products.create', ProductCreate::class);

        Livewire::component('hub.pages.product-options.product-options-index', ProductOptionsIndex::class);
        Livewire::component('hub.components.product-options.edit', OptionEdit::class);
        Livewire::component('hub.components.product-options.value-edit', OptionValueEdit::class);
    }

    protected function registerOrderComponents(): void
    {
        parent::registerOrderComponents();

        Livewire::component('hub.components.orders.discount', OrderDiscount::class);
        Livewire::component('hub.components.orders.show', OrderShow::class);
        Livewire::component('hub.components.orders.address', OrderAddress::class);
    }

    protected function registerCustomerComponents()
    {
        parent::registerCustomerComponents();

        Livewire::component('hub.components.customers.show', CustomerShow::class);
    }

    protected function registerFormComponents(): void
    {
        Livewire::component('hub.components.forms.channel-form', ChannelForm::class);
        Livewire::component('hub.components.forms.customer-detail-form', CustomerDetailForm::class);
        Livewire::component('hub.components.forms.profile-form', ProfileForm::class);
    }

    protected function registerSettingsComponents(): void
    {
        parent::registerSettingsComponents();

        Livewire::component('hub.components.settings.shippings.tables.list-shipping-zones', ListShippingZones::class);
        Livewire::component('hub.components.settings.shippings.tables.list-shipping-locations', ListShippingLocations::class);
        Livewire::component('hub.components.settings.shippings.tables.list-shipping-options', ListShippingOptions::class);
    }

    /**
     * Register the table components
     *
     * @return void
     */
    protected function registerLivewireTableComponents(): void
    {
        collect([
            /** @see \Lunar\Hub\Http\Livewire\Components\Orders\OrdersTable */
            'hub.components.orders.table' => 'Orders\OrdersTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Customers\CustomersTable */
            'hub.components.customers.table' => 'Customers\CustomersTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Products\Tables\ProductsTable */
            'hub.components.products.table' => 'Products\Tables\ProductsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Products\Tables\ProductTypesTable */
            'hub.components.products.product-types.table' => 'Products\Tables\ProductTypesTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Products\Tables\ProductVariantsTable */
            'hub.components.products.variants.table' => 'Products\Tables\ProductVariantsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Brands\BrandsTable */
            'hub.components.brands.table' => 'Brands\BrandsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\ActivityLogTable */
            'hub.components.settings.activity-log.table' => 'Settings\Tables\ActivityLogTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\AttributesTable */
            'hub.components.settings.attributes.table' => 'Settings\Tables\AttributesTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\ChannelsTable */
            'hub.components.settings.channels.table' => 'Settings\Tables\ChannelsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\StaffTable */
            'hub.components.settings.staff.table' => 'Settings\Tables\StaffTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\LanguagesTable */
            'hub.components.settings.languages.table' => 'Settings\Tables\LanguagesTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\TagsTable */
            'hub.components.settings.tags.table' => 'Settings\Tables\TagsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\CurrenciesTable */
            'hub.components.settings.currencies.table' => 'Settings\Tables\CurrenciesTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\AddonsTable */
            'hub.components.settings.addons.table' => 'Settings\Tables\AddonsTable',
            /** @see \Lunar\Hub\Http\Livewire\Components\Settings\Tables\TaxZonesTable */
            'hub.components.settings.taxes.tax-zones.table' => 'Settings\Tables\TaxZonesTable',
        ])->each(function ($viewClass, $alias) {
            $namespace = config('lunar-hub.system.components_namespace.tables', 'Lunar\\Hub\\Http\\Livewire\\Components');
            $component = $namespace.'\\'.$viewClass;
            if (! class_exists($component)) {
                $component = 'Lunar\\Hub\\Http\\Livewire\\Components\\'.$viewClass;
            }
            Livewire::component($alias, $component);
        });
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
            SidebarMenu::make();
            SettingsMenu::make();
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
