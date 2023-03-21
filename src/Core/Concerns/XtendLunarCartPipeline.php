<?php

namespace Xtend\Extensions\Lunar\Core\Concerns;

trait XtendLunarCartPipeline
{
    public function registerWithCartPipeline(array $pipelines): void
    {
        $lunarCartPipeline = config('lunar.cart.pipelines.cart');
        config(['lunar.cart.pipelines.cart' => array_merge($lunarCartPipeline, $pipelines)]);
    }
}
