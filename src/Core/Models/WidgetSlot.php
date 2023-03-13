<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetSlot extends Model
{
    protected $casts = [
        'params' => 'array',
    ];
}
