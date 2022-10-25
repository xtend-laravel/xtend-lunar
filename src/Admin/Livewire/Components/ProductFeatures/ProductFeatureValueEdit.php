<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\ProductFeatures;

use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Lunar\Models\ProductFeature;
use Lunar\Models\ProductFeatureValue;

class ProductFeatureValueEdit extends Component
{
    use WithLanguages;
    use Notifies;

    protected static $overrideComponentAlias = 'value-edit';

    /**
     * The feature instance.
     *
     * @var \Lunar\Models\ProductFeature
     */
    public ?ProductFeature $feature = null;

    /**
     * The feature value instance.
     *
     * @var \Lunar\Models\ProductFeatureValue
     */
    public ?ProductFeatureValue $featureValue = null;

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->featureValue = $this->featureValue ?: new ProductFeatureValue;

        if ($this->featureValue->id) {
            $this->feature = $this->featureValue->feature;
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
            $rules["featureValue.name.{$language->code}"] = ($language->default ? 'required' : 'nullable').'|max:255';
        }

        return $rules;
    }

    /**
     * Save the featureValue.
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        if (! $this->featureValue->id) {
            $this->featureValue->position = ProductFeatureValue::whereProductFeatureId(
                $this->feature->id
            )->count() + 1;

            // @todo Not sure why this is not working here?
            // $this->featureValue->increment('position');

            $this->featureValue->productFeature()->associate($this->feature);
            $this->featureValue->save();
            $this->notify(
                __('adminhub::notifications.attribute-edit.created')
            );
            $this->emit('feature-value-edit.created', $this->featureValue->id);

            return;
        }

        $this->featureValue->save();

        $this->notify(
            __('adminhub::notifications.attribute-edit.updated')
        );
        $this->emit('feature-value-edit.updated', $this->featureValue->id);
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.product-features.product-feature-value-edit')
            ->layout('adminhub::layouts.base');
    }
}
