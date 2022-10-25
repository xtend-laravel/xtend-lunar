<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Pages\ProductOptions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;

class ProductOptionsIndex extends Component
{
    use Notifies;
    use WithLanguages;

    /**
     * The type property.
     *
     * @var string
     */
    public $type;

    /**
     * The sorted product options.
     *
     * @var Collection
     */
    public Collection $sortedProductOptions;

    /**
     * Whether we should show the panel to create a new group.
     *
     * @var bool
     */
    public $showOptionCreate = false;

    /**
     * The option id to use for creating an attribute.
     *
     * @var int|null
     */
    public $valueCreateOptionId = null;

    /**
     * The id of the option to edit.
     *
     * @var int|null
     */
    public $editOptionId;

    /**
     * The id of the option to delete.
     *
     * @var int|null
     */
    public $deleteOptionId;

    /**
     * The id of the attribute to edit.
     *
     * @var int|null
     */
    public $editOptionValueId;

    /**
     * The ID of the attribute we want to delete.
     *
     * @var int|null
     */
    public $deleteOptionValueId;

    /**
     * {@inheritDoc}
     */
    protected $listeners = [
        'option-edit.created' => 'refreshGroups',
        'option-edit.updated' => 'resetGroupEdit',
        'option-value-edit.created' => 'resetOptionValueEdit',
        'option-value-edit.updated' => 'resetOptionValueEdit',
        'option-value-edit.closed' => 'resetOptionValueEdit',
    ];

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->sortedProductOptions = $this->productOptions;
    }

    /**
     * Return the product options.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProductOptionsProperty()
    {
        return ProductOption::orderBy('position')->get();
    }

    /**
     * Return the option to be used when creating an attribute.
     *
     * @return \Lunar\Models\ProductOption
     */
    public function getValueCreateOptionProperty()
    {
        return ProductOption::find($this->valueCreateOptionId);
    }

    /**
     * Sort the options.
     *
     * @param  array  $groups
     * @return void
     */
    public function sortGroups($groups)
    {
        DB::transaction(function () use ($groups) {
            $this->sortedProductOptions = $this->productOptions->map(function ($group) use ($groups) {
                $updatedOrder = collect($groups['items'])->first(function ($updated) use ($group) {
                    return $updated['id'] == $group->id;
                });
                $group->position = $updatedOrder['order'];
                $group->save();

                return $group;
            })->sortBy('position');
        });
        $this->notify(
            __('adminhub::notifications.attribute-groups.reordered')
        );
    }

    /**
     * Sort the option values.
     *
     * @param  array  $optionValues
     * @return void
     */
    public function sortOptionValues(array $optionValues)
    {
        DB::transaction(function () use ($optionValues) {
            foreach ($optionValues['items'] as $item) {
                ProductOptionValue::whereId($item['id'])->update([
                    'position' => $item['order'],
                    'product_option_id' => $item['parent'],
                ]);
            }
        });

        $this->refreshGroups();

        $this->notify(
            __('adminhub::notifications.attributes.reordered')
        );
    }

    /**
     * Refresh the options.
     *
     * @return void
     */
    public function refreshGroups()
    {
        $this->sortedProductOptions = ProductOption::orderBy('position')->get();

        $this->showOptionCreate = false;
    }

    /**
     * Return the computed property for the option to edit.
     *
     * @return \Lunar\Models\ProductOption|null
     */
    public function getOptionToEditProperty()
    {
        return ProductOption::find($this->editOptionId);
    }

    /**
     * Return the option marked for deletion.
     *
     * @return \Lunar\Models\ProductOption|null
     */
    public function getOptionToDeleteProperty(): ?ProductOption
    {
        return ProductOption::find($this->deleteOptionId);
    }

    /**
     * Return the option value to edit.
     *
     * @return \Lunar\Models\Attribute
     */
    public function getOptionValueToEditProperty()
    {
        return ProductOptionValue::find($this->editOptionValueId);
    }

    /**
     * Return the option value to delete.
     *
     * @return \Lunar\Models\ProductOptionValue|null
     */
    public function getOptionValueToDeleteProperty(): ?ProductOptionValue
    {
        return ProductOptionValue::find($this->deleteOptionValueId);
    }

    /**
     * Reset the group editing state.
     *
     * @return void
     */
    public function resetGroupEdit()
    {
        $this->editOptionId = null;
        $this->deleteOptionId = null;
        $this->refreshGroups();
    }

    /**
     * Reset the option value edit state.
     *
     * @return void
     */
    public function resetOptionValueEdit()
    {
        $this->deleteOptionValueId = null;
        $this->editOptionValueId = null;
        $this->refreshGroups();
    }

    public function deleteOption()
    {
        $level = 'error';
        $notificationText = 'product-options.not.deleted';
        if ($this->optionToDelete->values->isEmpty()) {
            $notificationText = 'product-options.deleted';
            $level = 'success';
            DB::transaction(function () {
                $this->optionToDelete->delete();
            });
        }

        $this->notify(
            __('adminhub::notifications.'.$notificationText), null, [], $level
        );

        $this->resetGroupEdit();
    }

    /**
     * Delete the option value.
     *
     * @return void
     */
    public function deleteOptionValue()
    {
        $level = 'error';
        $notificationText = 'option-values.not.deleted';
        if (! ProductVariant::whereHas('values', fn ($query) => $query->where('value_id', $this->optionValueToDelete->id))->count()) {
            $notificationText = 'option-values.deleted';
            $level = 'success';
            DB::transaction(function () {
                $this->optionValueToDelete->delete();
            });
        }

        $this->notify(
            __('adminhub::notifications.'.$notificationText), null, [], $level
        );

        $this->resetOptionValueEdit();
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.pages.product-options.index')
            ->layout('adminhub::layouts.app', [
                'title' => 'Product Options',
            ]);
    }
}
