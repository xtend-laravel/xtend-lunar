<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Products;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use Lunar\Facades\DB;
use Lunar\Hub\Jobs\Products\GenerateVariants;
use Lunar\Models\Brand;
use Lunar\Models\ProductAssociation;

class ProductShow extends AbstractProduct
{
    /**
     * Called when the component is mounted.
     *
     * @return void
     */
    public function mount()
    {
        $this->options = collect();
        $this->variantsEnabled = $this->getVariantsCount() > 1;
        $this->variant = $this->product->variants->first();

        $this->variantAttributes = $this->parseAttributes(
            $this->availableVariantAttributes,
            $this->variant->attribute_data,
            'variantAttributes',
        );

        $this->syncAvailability();
        $this->syncAssociations();
        $this->syncCollections();
    }

    /**
     * Universal method to handle saving the product.
     *
     * @return void|\Symfony\Component\HttpFoundation\Response
     */
    public function save()
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {
                if ($validator->errors()->count()) {
                    $this->notify(
                        __('adminhub::validation.generic'),
                        level: 'error'
                    );
                }
            });
        })->validate(null, $this->getValidationMessages());

        $this->validateUrls();
        $isNew = ! $this->product->id;

        DB::transaction(function () use ($isNew) {
            $data = $this->prepareAttributeData();
            $variantData = $this->prepareAttributeData($this->variantAttributes);

            $this->product->brand_id = $this->product->brand_id ?: null;

            if ($this->brand) {
                $brand = Brand::create([
                    'name' => $this->brand,
                ]);
                $this->product->brand_id = $brand->id;
                $this->brand = null;
                $this->useNewBrand = false;
            }
            $this->product->attribute_data = $data;

            $this->product->save();

            if (($this->getVariantsCount() <= 1) || $isNew) {
                if (! $this->variant->product_id) {
                    $this->variant->product_id = $this->product->id;
                }

                if (! $this->manualVolume) {
                    $this->variant->volume_unit = null;
                    $this->variant->volume_value = null;
                }

                $this->variant->attribute_data = $variantData;

                $this->variant->save();

                if ($isNew) {
                    $this->savePricing();
                }
            }

            // We generating variants?
            $generateVariants = (bool) count($this->optionValues) && ! $this->variantsDisabled;

            // if (! $this->variantsEnabled && $this->getVariantsCount()) {
            //     $variantToKeep = $this->product->variants()->first();
            //     $variantToKeep->values()->detach();
            //
            //     $variantsToRemove = $this->product->variants->filter(function ($variant) use ($variantToKeep) {
            //         return $variant->id != $variantToKeep->id;
            //     });
            //
            //     DB::transaction(function () use ($variantsToRemove) {
            //         foreach ($variantsToRemove as $variant) {
            //             $variant->values()->detach();
            //             $variant->prices()->delete();
            //             $variant->forceDelete();
            //         }
            //     });
            // }

            if ($generateVariants) {
                GenerateVariants::dispatch($this->product, $this->optionValues);
            }

            if (! $generateVariants && $this->product->variants->count() <= 1 && ! $isNew) {
                // Only save pricing if we're not generating new variants.
                $this->savePricing();
            }

            $this->saveUrls();

            $this->product->syncTags(
                collect($this->tags)
            );

            $this->updateImages($this->product);

            $channels = collect($this->availability['channels'])->mapWithKeys(function ($channel) {
                return [
                    $channel['channel_id'] => [
                        'starts_at' => ! $channel['enabled'] ? null : $channel['starts_at'],
                        'ends_at' => ! $channel['enabled'] ? null : $channel['ends_at'],
                        'enabled' => $channel['enabled'],
                    ],
                ];
            });

            $cgAvailability = collect($this->availability['customerGroups'])->mapWithKeys(function ($group) {
                $data = Arr::only($group, ['starts_at', 'ends_at']);

                $data['purchasable'] = $group['status'] == 'purchasable';
                $data['visible'] = in_array($group['status'], ['purchasable', 'visible']);
                $data['enabled'] = $group['status'] != 'hidden';

                return [
                    $group['customer_group_id'] => $data,
                ];
            });

            $this->product->customerGroups()->sync($cgAvailability);

            $this->product->channels()->sync($channels);

            if (count($this->associationsToRemove)) {
                ProductAssociation::whereIn('id', $this->associationsToRemove)->delete();
            }

            $this->associations->each(function ($assoc) {
                if (! empty($assoc['id'])) {
                    ProductAssociation::find($assoc['id'])->update([
                        'type' => $assoc['type'],
                    ]);

                    return;
                }

                ProductAssociation::firstOrCreate([
                    'product_target_id' => $assoc['inverse'] ? $this->product->id : $assoc['target_id'],
                    'product_parent_id' => $assoc['inverse'] ? $assoc['target_id'] : $this->product->id,
                    'type' => $assoc['type'],
                ]);
            });

            $this->product->collections()->detach(
                $this->collectionsToDetach->pluck('id')
            );

            $this->collections->each(function ($collection) {
                $this->product->collections()
                    ->syncWithoutDetaching(
                        $collection['id'],
                        ['position' => $collection['position']]
                    );
            });

            $this->updateSlots();

            $this->product->refresh();

            $this->variantsEnabled = $this->getVariantsCount() > 1;

            $this->syncAvailability();
            $this->syncAssociations();

            $this->dispatchBrowserEvent('remove-images');

            $this->variant = $this->product->variants->first();

            $this->notify('Product Saved');
        });

        if ($isNew) {
            return redirect()->route('hub.products.show', [
                'product' => $this->product->id,
            ]);
        }
    }

    /**
     * Delete the product.
     *
     * @return void
     */
    public function delete()
    {
        $this->product->delete();
        $this->notify(
            __('adminhub::notifications.products.deleted'),
            'hub.products.index'
        );
    }

    /**
     * Restore the product.
     *
     * @return void
     */
    public function restore()
    {
        $this->product->restore();
        $this->showRestoreConfirm = false;
        $this->notify(
            __('adminhub::notifications.products.product_restored')
        );
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // dd($this->attributeData);
        return view('adminhub::livewire.components.products.show')->layout('adminhub::layouts.base');
    }

    protected function getSlotContexts()
    {
        return ['product.all', 'product.show'];
    }
}
