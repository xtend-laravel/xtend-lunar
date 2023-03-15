<?php

namespace Xtend\Extensions\Lunar;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use FontLib\Table\Type\glyf;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Lunar\Base\ShippingModifiers;
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
use Xtend\Extensions\Lunar\Core\ShippingModifiers\FreeShipping;
use Xtend\Extensions\Lunar\Core\ShippingModifiers\UpsShippingModifier;
use Xtend\Extensions\Lunar\Providers\AdminHubServiceProvider;
use Xtend\Extensions\Lunar\Providers\LunarServiceProvider;
use Xtend\Extensions\Lunar\Slots\SeoSlot;
use Xtend\Extensions\Lunar\Slots\ShippingSlot;

class XtendLunarProvider extends ExtendsProvider
{
    /**
     * Extends register service provider
     *
     * @return void
     */
    public function register(): void
    {
        dd('register');
        $this->bootWithProviders();
    }

    protected function bootWithProviders(): void
    {
        $this->app->register(AdminHubServiceProvider::class);
    }

    /**
     * Extends boot service provider
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootWithModels();
        $this->bootWithSlots();
        $this->bootWithPaymentProviders();
        $this->bootWithShippingModifiers();
        $this->bootWithEvents();
        $this->bootWithFieldTypes();
        $this->bootWithComponents();
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
