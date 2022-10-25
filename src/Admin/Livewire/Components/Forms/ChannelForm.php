<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Forms;

use XtendLunar\Features\FormBuilder;

class ChannelForm extends FormBuilder\Base\LunarForm
{
    protected bool $showDeleteDangerZone = true;

    /**
     * Returns validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        $table = $this->model->getTable();

        return [
            'model.name' => 'required|string|max:255',
            'model.handle' => "required|string|unique:$table,handle,{$this->model->id}|max:255",
            'model.url' => 'nullable|url|max:255',
            'model.default' => 'nullable',
        ];
    }

    protected function schema(): array
    {
        return [
            FormBuilder\Fields\Text::make('name')->required(),
            FormBuilder\Fields\Text::make('handle')->required(),
            FormBuilder\Fields\Text::make('url')->required(),
            FormBuilder\Fields\Toggle::make('default')
                ->label('adminhub::inputs.default.label')
                ->required(),
        ];
    }
}
