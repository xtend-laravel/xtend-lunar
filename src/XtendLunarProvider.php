<?php

namespace Xtend\Extensions\Lunar;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Address;
use Lunar\Models\Brand;
use Lunar\Models\Cart;
use Lunar\Models\Collection as CollectionModel;
use Lunar\Models\Customer;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Order;
use Lunar\Models\Product;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;
use Xtend\Extensions\Lunar\Providers\AdminHubServiceProvider;
use XtendLunar\Features\FilamentTables\FilamentTablesProvider;
use XtendLunar\Features\FormBuilder\FormBuilderProvider;
use XtendLunar\Features\HubCustomTheme\HubCustomThemeProvider;
use XtendLunar\Features\LanguageSwitch\LanguageSwitchProvider;
use XtendLunar\Features\PaymentGateways\PaymentGatewaysProvider;
use XtendLunar\Features\ProductFeatures\ProductFeaturesProvider;
use XtendLunar\Features\ProductOptions\ProductOptionsProvider;
use XtendLunar\Features\ShippingProviders\ShippingProvidersProvider;
use XtendLunar\Features\SidebarMenu\SidebarMenuProvider;

class XtendLunarProvider extends ServiceProvider
{
    protected Collection $features;

    /**
     * Extends register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerWithFeatureSetup();
        $this->callAfterResolving('blade.compiler', fn() => $this->registerWithProviders());
    }

    protected function registerWithFeatureSetup(): void
    {
        // @todo move to parent xtend provider and check if the feature is active in the config
        Feature::define('xtend-lunar', fn () => true);

        // @todo Auto scan feature directories and check if the feature is installed and active in the config
        $this->features = collect([
            'hub-custom-theme' => HubCustomThemeProvider::class,
            'language-switch' => LanguageSwitchProvider::class,
            'sidebar-menu' => SidebarMenuProvider::class,
            'form-builder' => FormBuilderProvider::class,
            'filament-tables' => FilamentTablesProvider::class,
            'product-features' => ProductFeaturesProvider::class,
            'product-options' => ProductOptionsProvider::class,
            'payment-gateways' => PaymentGatewaysProvider::class,
            'shipping-providers' => ShippingProvidersProvider::class,
        ]);

        $this->features->each(function ($provider, $feature) {
            // @todo Set boolean for each feature which will be loaded from the config initially - later from the database in hub feature management section
            Feature::define($feature, fn () => true);
        });
    }

    protected function registerWithProviders(): void
    {
        if (Feature::inactive('xtend-lunar')) {
           return;
        }

        $this->app->register(AdminHubServiceProvider::class);

        $this->features->each(function ($provider, $feature) {
            if (Feature::active($feature)) {
                $this->app->register($provider);
            }
        });
    }

    /**
     * Extends boot service provider
     *
     * @return void
     */
    public function boot(): void
    {
        if (Feature::inactive('xtend-lunar')) {
           return;
        }

        $this->bootWithModels();
    }

    public function bootWithModels(): void
    {
        $models = collect([
            Product::class => \Xtend\Extensions\Lunar\Core\Models\Product::class,
            ProductVariant::class => \Xtend\Extensions\Lunar\Core\Models\ProductVariant::class,
            CollectionModel::class => \Xtend\Extensions\Lunar\Core\Models\Collection::class,
            ProductOption::class => \Xtend\Extensions\Lunar\Core\Models\ProductOption::class,
            ProductOptionValue::class => \Xtend\Extensions\Lunar\Core\Models\ProductOptionValue::class,
            Customer::class => \Xtend\Extensions\Lunar\Core\Models\Customer::class,
            CustomerGroup::class => \Xtend\Extensions\Lunar\Core\Models\CustomerGroup::class,
            Address::class => \Xtend\Extensions\Lunar\Core\Models\Address::class,
            Cart::class => \Xtend\Extensions\Lunar\Core\Models\Cart::class,
            Order::class => \Xtend\Extensions\Lunar\Core\Models\Order::class,
            Brand::class => \Xtend\Extensions\Lunar\Core\Models\Brand::class,
        ]);
        ModelManifest::register($models);
    }
}
