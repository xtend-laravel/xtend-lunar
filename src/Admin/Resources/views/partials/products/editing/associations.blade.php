<div class="shadow sm:rounded-md">
    <div class="flex-col space-y-4 rounded-md bg-white">

        <header class="flex items-center justify-between gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <div class="flex">
                <x-akar-info class="h-6 w-6 text-[#CFA55B]" />
                <span class="ml-2 text-sm font-semibold text-white">
                    {{ __('adminhub::partials.products.associations.heading') }}
                </span>
                <div class="ml-10 flex items-center space-x-2 text-xs">
                    <span class="@if ($showInverseAssociations) text-green-500 @endif">
                        {{ __('adminhub::partials.products.associations.show_inverse') }}
                    </span>
                    <x-hub::input.toggle wire:model="showInverseAssociations" />
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <x-hub::dropdown>
                    <x-slot name="value">
                        {{ __(
                            $showInverseAssociations
                                ? 'adminhub::partials.products.associations.add_inverse'
                                : 'adminhub::partials.products.associations.add_association',
                        ) }}
                    </x-slot>
                    <x-slot name="options">
                        <x-hub::dropdown.button wire:click.prevent="openAssociationBrowser('alternate')">
                            {{ __('adminhub::partials.products.associations.alternate') }}
                        </x-hub::dropdown.button>
                        <x-hub::dropdown.button wire:click.prevent="openAssociationBrowser('cross-sell')">
                            {{ __('adminhub::partials.products.associations.cross-sell') }}
                        </x-hub::dropdown.button>
                        <x-hub::dropdown.button wire:click.prevent="openAssociationBrowser('up-sell')">
                            {{ __('adminhub::partials.products.associations.up-sell') }}
                        </x-hub::dropdown.button>
                    </x-slot>
                </x-hub::dropdown>
                @livewire('hub.components.product-search', [
                    'existing' => $this->associatedProductIds,
                    'ref' => 'product-associations',
                    'showBtn' => false,
                    'exclude' => [$product->id],
                ])
            </div>
        </header>

        <div>
            <x-hub::table>
                <x-slot name="head">
                    <x-hub::table.heading class="w-24">

                    </x-hub::table.heading>
                    <x-hub::table.heading>
                        {{ __('adminhub::global.product') }}
                    </x-hub::table.heading>
                    <x-hub::table.heading>
                        {{ __('adminhub::global.type') }}
                    </x-hub::table.heading>
                    <x-hub::table.heading></x-hub::table.heading>
                </x-slot>
                <x-slot name="body">
                    @foreach ($associations->filter(fn($product) => $product['inverse'] == $showInverseAssociations) as $index => $product)
                        <x-hub::table.row>
                            <x-hub::table.cell>
                                <img src="{{ $product['thumbnail'] }}" class="w-12 rounded">
                            </x-hub::table.cell>
                            <x-hub::table.cell>{{ $product['name'] }}</x-hub::table.cell>
                            <x-hub::table.cell>
                                <x-hub::input.select wire:model="associations.{{ $index }}.type">
                                    <option value="alternate">
                                        {{ __('adminhub::partials.products.associations.alternate') }}
                                    </option>
                                    <option value="cross-sell">
                                        {{ __('adminhub::partials.products.associations.cross-sell') }}
                                    </option>
                                    <option value="up-sell">
                                        {{ __('adminhub::partials.products.associations.up-sell') }}
                                    </option>
                                </x-hub::input.select>
                            </x-hub::table.cell>
                            <x-hub::table.cell>
                                <button type="button" wire:click.prevent="removeAssociation({{ $index }})"
                                    class="text-red-500 hover:underline">
                                    {{ __('adminhub::global.remove') }}
                                </button>
                            </x-hub::table.cell>
                        </x-hub::table.row>
                    @endforeach
                </x-slot>
            </x-hub::table>
        </div>
    </div>
</div>
