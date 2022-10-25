<?php

namespace Xtend\Extensions\Lunar\Slots;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;

class SeoSlot extends Component implements AbstractSlot
{
    use HubSlot;

    protected $rules = [
        'slotModel.field_a' => 'required',
        'slotModel.field_b' => 'required',
    ];

    public static function getName()
    {
        return 'hub.products.slots.seo-slot';
    }

    public function getSlotHandle()
    {
        return 'seo-slot';
    }

    public function getSlotInitialValue()
    {
        return [
        ];
    }

    public function getSlotPosition()
    {
        return 'top';
    }

    public function getSlotTitle()
    {
        return 'SEO';
    }

    public function submit()
    {
        $this->validate();
    }

    public function handleSlotSave($model, $data)
    {
        $validator = Validator::make($data, $this->rules);

        return $validator->fails() ? $validator->errors() : [];
    }

    public function render()
    {
        return view('adminhub::livewire.components.seo-slot');
    }
}
