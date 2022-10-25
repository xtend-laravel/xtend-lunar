<form wire:submit.prevent="submit">
    <x-hub::input.group for="carrier_name" label="Carrier name:" :error="$errors->first('slotModel.carrier_name')">
        <x-hub::input.text id="carrier_name" wire:model="slotModel.carrier_name" :error="$errors->first('slotModel.carrier_name')" />
    </x-hub::input.group>

    <x-hub::input.group for="carrier_price" label="Carrier price:" :error="$errors->first('slotModel.carrier_price')">
        <x-hub::input.text id="carrier_price" wire:model="slotModel.carrier_price" :error="$errors->first('slotModel.carrier_price')" />
    </x-hub::input.group>

    <x-hub::button type="submit" class="mt-4">
        {{ __('adminhub::components.orders.show.save_shipping_btn') }}
    </x-hub::button>
</form>
