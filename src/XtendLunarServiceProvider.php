<?php

namespace Xtend\Extensions\Lunar;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Collection;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Lunar\Facades\ModelManifest;
use Lunar\Facades\Payments;
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
use Xtend\Extensions\Lunar\Core\PaymentTypes\Paypal;
use Xtend\Extensions\Lunar\Core\PaymentTypes\Payzen;
use Xtend\Extensions\Lunar\Providers\AdminHubServiceProvider;
use Xtend\Extensions\Lunar\Slots\SeoSlot;
use Xtend\Extensions\Lunar\Slots\ShippingSlot;
use XtendLunar\Features\FormBuilder\FormBuilderProvider;
use XtendLunar\Features\HubCustomTheme\HubCustomThemeProvider;

class XtendLunarServiceProvider extends ExtendsProvider
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

        // @todo Auto scan feature directories and check if the feature is active in the config
        $this->features = collect([
            'hub-custom-theme' => HubCustomThemeProvider::class,
            'form-builder' => FormBuilderProvider::class,
        ]);

        $this->features->each(function ($provider, $feature) {
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
        $this->bootWithSlots();
        $this->bootWithPaymentProviders();
        $this->bootWithShippingModifiers();
        $this->bootWithEvents();
        $this->bootWithFieldTypes();
        $this->bootWithComponents();

        $this->offerPublishing();
        $this->registerCommands();
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

    protected function bootWithSlots(): void
    {
        Livewire::component('hub.products.slots.seo-slot', SeoSlot::class);
        Livewire::component('hub.orders.slots.shipping-slot', ShippingSlot::class);
    }

    protected function bootWithPaymentProviders(): void
    {
        Payments::extend('paypal', fn ($app) => $app->make(Paypal::class));
        Payments::extend('payzen', fn ($app) => $app->make(Payzen::class));
    }

    protected function bootWithShippingModifiers(): void
    {
        // $shippingModifiers = resolve(ShippingModifiers::class);
        // $shippingModifiers->add(FreeShipping::class);
        // $shippingModifiers->add(UpsShippingModifier::class);
    }

    protected function bootWithEvents(): void
    {
    }

    protected function bootWithFieldTypes()
    {
    }

    protected function bootWithComponents()
    {
    }

    /**
     * Set up the resource publishing groups for XtendLaravel.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/XtendLunarServiceProvider.stub' => app_path('Providers/XtendLunarServiceProvider.php'),
            ], 'xtend-lunar-provider');
        }
    }

    /**
     * Register the XtendLunar Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
            ]);
        }
    }

    /**
     * Extend Hub Assets
     *
     * @todo This will add support to push to stacks not used right now.
     *
     * @param  \Illuminate\Routing\Events\RouteMatched  $event
     * @return void
     */
    protected function extendHubAssets(RouteMatched $event): void
    {
        if (! str_starts_with($event->route->getName(), 'hub.')) {
            return;
        }

        app('view')->startPush('hub-styles', collect([
            '<style>',
            //'',
            'footer { background: transparent!important; };',
            '</style>',
        ])->implode("\n"));

        app('view')->startPush('hub-scripts', collect([
            'console.info(\'Extend hub scripts\');',
            'Livewire.hook(\'component.initialized\', (component) => console.info(component.name));',
        ])->map(fn ($script) => "<script>{$script}</script>")->implode("\n"));
    }
}
