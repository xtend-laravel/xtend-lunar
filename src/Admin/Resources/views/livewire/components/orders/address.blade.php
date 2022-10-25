<header class="flex h-full flex-col items-center justify-between">
    <div class="hidden font-bold text-gray-700 sm:block">
        {{ $heading }}
    </div>

    <div class="flex-1">
        <x-hub::input.select wire:model="addressId" required>
            <option value="0" readonly>Select another address</option>
            @foreach ($addressesOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </x-hub::input.select>

        @if ($address && $address->id)
            @if (!$hidden)
                <address class="mt-4 text-sm not-italic text-gray-600">
                    @if ($address->company_name)
                        {{ $address->company_name }} <br>
                    @endif
                    {{ $address->fullName }} <br>
                    {{ $address->line_one }} <br>

                    @if ($address->line_two)
                        {{ $address->line_two }} <br>
                    @endif

                    @if ($address->line_three)
                        {{ $address->line_three }} <br>
                    @endif

                    @if ($address->city)
                        {{ $address->city }} <br>
                    @endif

                    @if ($address->state)
                        {{ $address->state }} <br>
                    @endif

                    {{ $address->postcode }} <br>

                    {{ $address->country?->name }}

                    <div class="mt-2">
                        <div class="flex items-center">
                            <x-hub::icon ref="phone" class="mr-2 w-4" />
                            @if ($address->contact_phone)
                                <a href="tel:{{ $address->contact_phone }}"
                                    class="text-blue-600 underline">{{ $address->contact_phone }}</a>
                            @else
                                <span class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
                            @endif
                        </div>

                        <div class="flex items-center">
                            <x-hub::icon ref="mail" class="mr-2 w-4" />
                            @if ($address->contact_email)
                                <a href="mailto:{{ $address->contact_email }}"
                                    class="text-blue-600 underline">{{ $address->contact_email }}</a>
                            @else
                                <span class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
                            @endif
                        </div>
                    </div>
                </address>
            @else
                <span class="text-sm text-gray-600">{{ $message ?? null }}</span>
            @endif
        @else
            <span class="text-sm text-gray-600">
                {{ __('adminhub::partials.orders.address.not_set') }}
            </span>
        @endif
    </div>

    <div class="mt-4 flex w-full items-center justify-end">
        <x-hub::button type="button" wire:click.prevent="$set('showAddressEditConfirmation', true)"
            class="bg-[#CFA55B] text-white hover:bg-[#CFA55B]">
            {{ __('Update') }}
        </x-hub::button>
    </div>

    <x-hub::modal.dialog wire:model="showAddressEditConfirmation">
        <x-slot name="title">
            {{ __('Update address') }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600">
                {{ __("Are you sure you want to update {$type} address?") }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-2">
                <x-hub::button type="button" wire:click.prevent="$set('showAddressEditConfirmation', false)"
                    class="bg-gray-300 text-black hover:bg-gray-300 hover:text-black">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button type="button" wire:click.prevent="updateAddress"
                    class="bg-[#CFA55B] text-white hover:bg-[#CFA55B]">
                    {{ __('Update') }}
                </x-hub::button>
            </div>
        </x-slot>
    </x-hub::modal.dialog>
</header>
