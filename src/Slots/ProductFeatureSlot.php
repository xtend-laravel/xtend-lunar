<?php

namespace Xtend\Extensions\Lunar\Slots;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;
use Xtend\Extensions\Lunar\Core\Models\ProductFeature;
use Xtend\Extensions\Lunar\Core\Models\ProductFeatureValue;

class ProductFeatureSlot extends Component implements AbstractSlot, HasForms
{
    use HubSlot;
    use InteractsWithForms;

    public array $features = [];

    protected $rules = [
        'features' => 'required|array',
    ];

    public function mount()
    {
        /** @var ProductFeatureValue $feature */
        foreach ($this->slotModel->featureValues ?? [] as $featureValue) {
            $this->features[] = [
                'feature' => $featureValue->product_feature_id,
                'feature_value' => $featureValue->id,
            ];
        }
    }

    public static function getName()
    {
        return 'hub.components.products.slots.product-feature-slot';
    }

    public function getSlotHandle()
    {
        return 'product-feature-slot';
    }

    public function getSlotInitialValue()
    {
        return [];
    }

    public function getSlotPosition()
    {
        return 'bottom';
    }

    public function getSlotTitle()
    {
        return 'Features';
    }

    public function updateSlotModel()
    {
        $this->validate();

        /** @var \Xtend\Extensions\Lunar\Core\Models\Product $model */
        $model = $this->slotModel;
        $model->featureValues()->sync(collect($this->features)->pluck('feature_value'));
    }

    public function handleSlotSave($model, $data)
    {
        //dd($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('features')
                ->disableItemMovement()
                ->disableLabel()
                ->schema([
                    Select::make('feature')
                        ->options(ProductFeature::all()->mapWithKeys(fn (ProductFeature $feature) => [$feature->id => $feature->translate('name')]))
                        ->reactive()
                        ->dehydrated(false)
                        ->required(),
                    Select::make('feature_value')
                        ->disabled(fn (Closure $get) => ! $get('feature'))
                        ->options(function (Closure $get) {
                            $feature = ProductFeature::find($get('feature'));
                            if ($feature && $feature->values) {
                                return $feature->values->mapWithKeys(fn (ProductFeatureValue $feature) => [$feature->id => $feature->translate('name')]);
                            }
                            return [];
                        })
                        ->required(),
                ])
                ->columns(),
        ];
    }

    public function render()
    {
        return view('adminhub::livewire.components.product-feature-slot');
    }
}
