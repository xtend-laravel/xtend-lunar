<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Products;

use Lunar\Hub\Http\Livewire\Components\Products\AbstractProduct as LunarAbstractProduct;
use Xtend\Extensions\Lunar\Admin\Livewire\Concerns\HasUrls;

abstract class AbstractProduct extends LunarAbstractProduct
{
    use HasUrls;
}
