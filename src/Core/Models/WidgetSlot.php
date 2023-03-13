<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetSlot extends Model
{
    protected $table = 'xtend_builder_widget_slots';

    protected $casts = [
        'params' => 'array',
    ];
}
