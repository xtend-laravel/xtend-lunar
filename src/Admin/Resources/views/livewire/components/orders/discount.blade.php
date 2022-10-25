<div class="space-y-4">
    <x-hub::input.group for="amount" :label="__('adminhub::inputs.amount.label')" required :error="$errors->first('amount')">
        <x-hub::input.price wire:model="amount" :symbol="$order->currency->format" :currencyCode="$order->currency->code" required />
    </x-hub::input.group>

    <x-hub::input.group for="notes" :label="__('adminhub::inputs.notes.label')">
        <x-hub::input.textarea wire:model="notes" />
    </x-hub::input.group>

    <x-hub::input.group for="confirm" :label="__('adminhub::inputs.confirm.label')" :instructions="__('Please confirm you wish to discount this amount on this order.')">
        <x-hub::input.toggle wire:model="confirmed" />
    </x-hub::input.group>

    <div class="flex items-center justify-between">
        <x-hub::button type="button" wire:click.prevent="cancel" theme="gray">
            {{ __('adminhub::global.cancel') }}
        </x-hub::button>

        <x-hub::button :disabled="!$confirmed" wire:click.prevent="discount" type="button">
            <div wire:loading wire:target="discount">
                <x-hub::icon ref="refresh" class="inline-block w-4 rotate-180 animate-spin" />
            </div>
            <div wire:loading.remove wire:target="discount">
                {{ __('Apply Discount') }}
            </div>
        </x-hub::button>
    </div>

    @if ($this->discountError)
        <x-hub::alert level="danger">
            {{ $this->discountError }}
        </x-hub::alert>
    @endif
</div>
