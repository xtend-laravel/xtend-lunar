<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CollectionProduct extends Pivot
{
    public function setTable($table)
    {
        $prefix = config('lunar.database.table_prefix');
        $this->table = str_starts_with($table, $prefix) ? $table : $prefix.$table;

        return $this;
    }
}
