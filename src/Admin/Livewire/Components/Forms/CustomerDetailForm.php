<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Forms;

use XtendLunar\Features\FormBuilder;

class CustomerDetailForm extends FormBuilder\Base\LunarForm
{
    public string $layout = 'slideover';

    /**
     * {@inheritdoc}
     */
    protected function rules(): array
    {
        return [
            'model.title' => 'required',
            'model.first_name' => 'required',
            'model.last_name' => 'required',
            'model.email' => 'required|email',
            //'model.language' => 'required',
            'model.newsletter' => 'nullable|bool',
            'model.company_name' => 'nullable|string',
            'model.vat_no' => 'nullable|string',
            'model.customerGroups' => 'nullable',
        ];
    }

    /**
     * Setup form schema.
     *
     * @return array
     */
    protected function schema(): array
    {
        return [
            FormBuilder\Fields\Select::make('title')->options(['Mr.', 'Mrs.']),
            FormBuilder\Fields\Text::make('first_name')->required(),
            FormBuilder\Fields\Text::make('last_name')->required(),
            FormBuilder\Fields\Text::make('email'),
            // FormBuilder\Fields\Select::make('language')->options(
            //     options: Language::all()->mapWithKeys(fn ($language) => [$language->id => $language->name])->toArray(),
            //     relationship: true,
            // ),
            FormBuilder\Fields\Toggle::make('newsletter'),
            FormBuilder\Fields\Text::make('company_name'),
            FormBuilder\Fields\Text::make('vat_no'),
            FormBuilder\Fields\Tags::make('customerGroups'),
        ];
    }
}
