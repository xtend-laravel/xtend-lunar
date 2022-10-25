<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductOptions;

use Illuminate\Support\Str;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Lunar\Models\ProductOption;

class OptionEdit extends Component
{
    use WithLanguages;
    use Notifies;

    protected static $overrideComponentAlias = 'edit';

    /**
     * The option to edit.
     *
     * @var \Lunar\Models\ProductOption
     */
    public ?ProductOption $productOption = null;

    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->languages as $language) {
            $rules["productOption.name.{$language->code}"] = ($language->default ? 'required' : 'nullable').'|max:255';
        }

        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->productOption = $this->productOption ?: new ProductOption();
    }

    public function create()
    {
        $this->validate();

        $handle = Str::handle("{$this->productOption->translate('name')}");
        $this->productOption->handle = $handle;

        $this->validate([
            'productOption.handle' => 'unique:'.get_class($this->productOption).',handle',
        ]);

        if ($this->productOption->id) {
            $this->productOption->save();
            $this->emit('option-edit.updated', $this->productOption->id);
            $this->notify(
                __('adminhub::notifications.attribute-groups.updated')
            );

            return;
        }

        $this->productOption->position = ProductOption::count() + 1;
        $this->productOption->handle = $handle;
        $this->productOption->save();

        $this->emit('option-edit.created', $this->productOption->id);

        $this->productOption = new ProductOption();

        $this->notify(
            __('adminhub::notifications.attribute-groups.created')
        );
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.product-options.option-edit')
            ->layout('adminhub::layouts.base');
    }
}
