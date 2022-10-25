<form wire:submit.prevent="submit">
    <x-hub::input.group for="field_a" label="Field A:" :error="$errors->first('slotModel.field_a')">
        <x-hub::input.text id="field_a" wire:model="slotModel.field_a" :error="$errors->first('slotModel.field_a')" />
    </x-hub::input.group>

    <x-hub::input.group for="field_b" label="Field B:" :error="$errors->first('slotModel.field_b')">
        <x-hub::input.text id="field_b" wire:model="slotModel.field_b" :error="$errors->first('slotModel.field_b')" />
    </x-hub::input.group>

    <x-hub::button type="submit" class="mt-4">
        {{ __('adminhub::account.save_btn') }}
    </x-hub::button>
</form>
