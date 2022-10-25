<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Traits;

trait HasRules
{
    /**
     * Returns validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'model.name' => 'required|string|max:255',
        ];
    }
}
