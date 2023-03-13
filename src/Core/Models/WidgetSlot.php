<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Lunar\Base\BaseModel;

class WidgetSlot extends BaseModel
{
    protected $casts = [
        'params' => 'array',
    ];
}
