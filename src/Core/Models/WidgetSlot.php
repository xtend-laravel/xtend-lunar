<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WidgetSlot extends Model
{
    protected $table = 'xtend_builder_widget_slots';

    protected $primaryKey = 'identifier';

    protected $casts = [
        'params' => 'array',
    ];

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Widget::class,
            table: 'xtend_builder_widget_slot_item',
            foreignPivotKey: 'widget_slot_id',
            relatedPivotKey: 'widget_id',
            parentKey: 'id',
        );
    }
}
