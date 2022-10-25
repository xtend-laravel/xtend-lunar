<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Traits;

trait HasListeners
{
    public function getListeners()
    {
        return array_merge($this->listeners, [
            'onCreateForm' => 'create',
            'onUpdateForm' => 'update',
        ]);
    }
}
