<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Lunar\Base\BaseModel;

class Widget extends BaseModel
{
    protected $casts = [
        'data' => 'array',
    ];
}
