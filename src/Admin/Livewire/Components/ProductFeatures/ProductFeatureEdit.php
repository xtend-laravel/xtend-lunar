<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductFeatures;

use Illuminate\Support\Str;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Xtend\Extensions\Lunar\Core\Models\ProductFeature;

class ProductFeatureEdit extends Component
{
    use WithLanguages;
    use Notifies;

    protected static $overrideComponentAlias = 'edit';

    /**
     * The feature to edit.
     *
     * @var \Xtend\Extensions\Lunar\Core\Models\ProductFeature
     */
    public ?ProductFeature $productFeature = null;

    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->languages as $language) {
            $rules["productFeature.name.{$language->code}"] = ($language->default ? 'required' : 'nullable').'|max:255';
        }

        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->productFeature = $this->productFeature ?: new ProductFeature();
    }

    public function create()
    {
        $this->validate();

        $handle = Str::handle("{$this->productFeature->translate('name')}");
        $this->productFeature->handle = $handle;

        $this->validate([
            'productFeature.handle' => 'unique:'.get_class($this->productFeature).',handle',
        ]);

        if ($this->productFeature->id) {
            $this->productFeature->save();
            $this->emit('feature-edit.updated', $this->productFeature->id);
            $this->notify(
                __('adminhub::notifications.attribute-groups.updated')
            );

            return;
        }

        $this->productFeature->position = ProductFeature::count() + 1;
        $this->productFeature->handle = $handle;
        $this->productFeature->save();

        $this->emit('feature-edit.created', $this->productFeature->id);

        $this->productFeature = new ProductFeature();

        $this->notify(
            __('adminhub::notifications.attribute-groups.created')
        );
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.product-features.product-feature-edit')
            ->layout('adminhub::layouts.base');
    }
}
