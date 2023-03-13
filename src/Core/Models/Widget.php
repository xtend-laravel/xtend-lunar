<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'xtend_builder_widgets';

    protected $casts = [
        'data' => 'array',
    ];
}
