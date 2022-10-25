<?php

namespace Xtend\Extensions\Lunar\Core\ShippingModifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;

class UpsShippingModifier extends ShippingModifier
{
    public function handle(Cart $cart)
    {
        // Get the tax class
        $taxClass = TaxClass::first();

        if (! $cart->order) {
            return;
        }

        $shippingPrice = $this->convertIntPrice($cart->order->legacy_data->get('total_shipping_tax_excl') ?: 0);
        //dump('UpsShippingModifier::handle', $shippingPrice);

        ShippingManifest::addOption(
            new ShippingOption(
                description: 'UPS',
                identifier: 'UPS',
                price: new Price($shippingPrice, $cart->currency, 1),
                taxClass: $taxClass,
            )
        );
    }

    protected function convertIntPrice(mixed $price): int
    {
        return (int) number_format($price, 2, '', '');
    }
}
