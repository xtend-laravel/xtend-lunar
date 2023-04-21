<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Product\Options;

use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;

class OptionValueEdit extends Component
{
    use WithLanguages;
    use Notifies;

    /**
     * The option instance.
     *
     * @var \Lunar\Models\ProductOption
     */
    public ?ProductOption $option = null;

    /**
     * The option value instance.
     *
     * @var \Lunar\Models\ProductOptionValue
     */
    public ?ProductOptionValue $optionValue = null;

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->optionValue = $this->optionValue ?: new ProductOptionValue;

        if ($this->optionValue->id) {
            $this->option = $this->optionValue->option;
        }
    }

    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->languages as $language) {
            $rules["optionValue.name.{$language->code}"] = ($language->default ? 'required' : 'nullable').'|max:255';
        }

        return $rules;
    }

    /**
     * Save the optionValue.
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        if (! $this->optionValue->id) {
            $this->optionValue->position = ProductOptionValue::whereProductOptionId(
                $this->option->id
            )->count() + 1;

            $this->optionValue->option()->associate($this->option);
            $this->optionValue->save();
            $this->notify(
                __('adminhub::notifications.attribute-edit.created')
            );
            $this->emit('option-value-edit.created', $this->optionValue->id);

            return;
        }

        $this->optionValue->save();

        $this->notify(
            __('adminhub::notifications.attribute-edit.updated')
        );
        $this->emit('option-value-edit.updated', $this->optionValue->id);
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.settings.product.options.option-value-edit')
            ->layout('adminhub::layouts.base');
    }
}
