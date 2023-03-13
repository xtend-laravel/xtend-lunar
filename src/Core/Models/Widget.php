<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $casts = [
        'data' => 'array',
    ];
}
