<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Widget extends Model
{
    protected $table = 'xtend_builder_widgets';

    protected $casts = [
        'data' => 'array',
        'params' => 'array',
    ];

    protected $fillable = [
        'type',
        'name',
        'component',
        'col_start',
        'row_start',
        'cols',
        'rows',
        'data',
        'params',
        'enabled',
    ];

    public function slots(): BelongsToMany
    {
        return $this->belongsToMany(
            related: WidgetSlot::class,
            table: 'xtend_builder_widget_slot_item',
            foreignPivotKey: 'widget_id',
            relatedPivotKey: 'widget_slot_id',
        );
    }
}
