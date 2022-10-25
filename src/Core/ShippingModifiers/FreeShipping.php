<?php

namespace Xtend\Extensions\Lunar\Core\ShippingModifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;

class FreeShipping extends ShippingModifier
{
    public function handle(Cart $cart)
    {
        // Get the tax class
        $taxClass = TaxClass::first();

        ShippingManifest::addOption(
            new ShippingOption(
                description: 'Free Shipping',
                identifier: 'FREE_SHIPPING',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass
            )
        );
    }
}
