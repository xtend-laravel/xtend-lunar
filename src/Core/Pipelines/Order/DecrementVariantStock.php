<?php

namespace Xtend\Extensions\Lunar\Core\Pipelines\Order;

use Closure;
use Lunar\FieldTypes\Text;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;
use Lunar\Models\ProductVariant;

class DecrementVariantStock
{
    /**
     * @return Closure
     */
    public function handle(Order $order, Closure $next)
    {
        $order->lines
            ->filter(fn (OrderLine $line) => $line->purchasable_type === ProductVariant::class)
            ->filter(fn (OrderLine $line) => $line->purchasable->attribute_data['availability']->getValue() === 'in-stock')
            ->each(function (OrderLine $line) {
                /** @var ProductVariant $variant */
                $variant = $line->purchasable;
                if ($variant->stock > 1) {
                    $variant->decrement('stock', $line->quantity);
                } else {
                    $variant->attribute_data = [
                        'availability' => new Text('pre-order'),
                    ];
                    $variant->stock = 9999;
                    $variant->save();
                }
            });

        return $next($order);
    }
}
