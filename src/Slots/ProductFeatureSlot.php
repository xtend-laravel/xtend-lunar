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

    protected array $features = [];

    protected $rules = [
        'features' => 'required|array',
    ];

    public function mount()
    {
        /** @var ProductFeatureValue $feature */
        foreach ($this->getSlotModel->featureValues ?? [] as $feature) {
            $this->features[] = [
                'feature' => $feature->id,
                'feature_value' => $feature->product_feature_id,
            ];
        }
    }

    public static function getName()
    {
        return 'hub.products.slots.product-feature-slot';
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
    }

    public function handleSlotSave($model, $data)
    {
        dd($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('features')
                    ->schema([
                        Select::make('feature')
                              ->options(ProductFeature::all()->mapWithKeys(fn(ProductFeature $feature) => [$feature->id => $feature->translate('name')]))
                              ->reactive()
                              ->dehydrated(false)
                              ->required(),
                        Select::make('feature_value')
                              ->options(function (Closure $get) {
                                  $feature = ProductFeature::find($get('feature'));
                                  if ($feature && $feature->values) {
                                      return $feature->values->mapWithKeys(fn(ProductFeatureValue $feature) => [$feature->id => $feature->translate('name')]);
                                  }

                                  return [];
                              })
                              ->required(),
                    ])
                    ->columns(2)
            // ...
        ];
    }

    public function render()
    {
        return view('adminhub::livewire.components.product-feature-slot');
    }
}
