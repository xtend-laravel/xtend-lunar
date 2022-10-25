<div class="space-y-4">
    <header class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white md:text-2xl">
            {{ __('adminhub::components.product.options.index.title') }}
        </h1>

        <div class="mt-4 sm:mt-0">
            <x-hub::button wire:click.prevent="$set('showOptionCreate', true)">
                {{ __('adminhub::components.product.options.index.create_btn') }}
            </x-hub::button>
        </div>
    </header>

    <div wire:sort sort.options='{group: "groups", method: "sortGroups"}' class="space-y-2">
        @forelse($sortedProductOptions as $option)
            <div wire:key="group_{{ $option->id }}" x-data="{ expanded: false }" sort.item="groups"
                sort.id="{{ $option->id }}">
                <div class="flex items-center">
                    <div wire:loading wire:target="sort">
                        <x-hub::icon ref="refresh" style="solid"
                            class="mr-2 w-5 rotate-180 animate-spin text-gray-300" />
                    </div>

                    <div wire:loading.remove wire:target="sort">
                        <div sort.handle class="cursor-grab">
                            <x-hub::icon ref="selector" style="solid" class="mr-2 text-gray-400 hover:text-gray-700" />
                        </div>
                    </div>

                    <div
                        class="sort-item-element flex w-full items-center justify-between rounded border border-transparent bg-white p-3 text-sm shadow-sm hover:border-gray-300">
                        <div class="expand flex items-center justify-between">
                            {{ $option->translate('name') }}
                        </div>
                        <div class="flex">
                            @if ($option->values->count())
                                <button @click="expanded = !expanded">
                                    <div class="transition-transform"
                                        :class="{
                                            '-rotate-90 ': expanded
                                        }">
                                        <x-hub::icon ref="chevron-left" style="solid" />
                                    </div>
                                </button>
                            @endif
                            <x-hub::dropdown minimal>
                                <x-slot name="options">
                                    <x-hub::dropdown.button x-cloak
                                        wire:click="$set('editOptionId', {{ $option->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        {{ __('adminhub::components.option.edit_group_btn') }}
                                    </x-hub::dropdown.button>

                                    <x-hub::dropdown.button x-cloak
                                        wire:click="$set('valueCreateOptionId', {{ $option->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                        {{ __('adminhub::components.option.create_option_value') }}
                                    </x-hub::dropdown.button>

                                    <x-hub::dropdown.button x-cloak
                                        wire:click="$set('deleteOptionId', {{ $option->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                        <span
                                            class="text-red-500">{{ __('adminhub::components.option.delete_group_btn') }}</span>
                                    </x-hub::dropdown.button>
                                </x-slot>
                            </x-hub::dropdown>
                        </div>
                    </div>
                </div>
                <div x-cloak class="mt-2 ml-8 space-y-2 rounded border-l bg-opacity-5 py-4 pl-2 pr-4"
                    @if ($option->values->count()) x-show="expanded" @endif>
                    <div class="space-y-2" wire:sort
                        sort.options='{group: "values", method: "sortOptionValues", owner: {{ $option->id }}}'
                        x-show="expanded">
                        @foreach ($option->values as $optionValue)
                            <div x-cloak
                                class="sort-item-element flex w-full items-center justify-between rounded border border-transparent bg-white p-3 text-sm shadow-sm hover:border-gray-300"
                                wire:key="attribute_{{ $optionValue->id }}" sort.item="values"
                                sort.parent="{{ $option->id }}" sort.id="{{ $optionValue->id }}">
                                <div sort.handle class="cursor-grab">
                                    <x-hub::icon ref="selector" style="solid"
                                        class="mr-2 text-gray-400 hover:text-gray-700" />
                                </div>
                                <span class="grow truncate">{{ $optionValue->translate('name') }}</span>
                                <div class="mr-4 text-xs text-gray-500">
                                    {{ $optionValue->type }}
                                </div>
                                <div>
                                    <x-hub::dropdown minimal>
                                        <x-slot name="options">
                                            <x-hub::dropdown.button x-cloak type="button"
                                                wire:click="$set('editOptionValueId', {{ $optionValue->id }})"
                                                class="flex items-center justify-between border-b px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                {{ __('adminhub::components.option.edit_option.value.btn') }}
                                                <x-hub::icon ref="pencil" style="solid" class="w-4" />
                                            </x-hub::dropdown.button>

                                            <x-hub::dropdown.button x-cloak
                                                wire:click="$set('deleteOptionValueId', {{ $optionValue->id }})"
                                                class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                                <span
                                                    class="text-red-500">{{ __('adminhub::components.option.delete_option.value.btn') }}</span>
                                            </x-hub::dropdown.button>
                                        </x-slot>
                                    </x-hub::dropdown>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (!$option->values->count())
                        <span class="mx-4 text-sm text-gray-500">
                            {{ __('adminhub::components.option.no_option values_text') }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div x-cloak class="w-full text-center text-gray-500">
                {{ __('adminhub::components.option.no_groups') }}
            </div>
        @endforelse
    </div>

    <x-hub::modal.dialog wire:model="showOptionCreate">
        <x-slot name="title">{{ __('adminhub::components.option.create_title') }}</x-slot>
        <x-slot name="content">
            @livewire('hub.components.product-options.edit')
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-hub::modal.dialog>

    @if ($this->optionToEdit)
        <x-hub::modal.dialog wire:model="editOptionId">
            <x-slot name="title">{{ __('adminhub::components.option.edit_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-options.edit', [
                    'productOption' => $this->optionToEdit,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->optionToDelete)
        <x-hub::modal.dialog wire:model="deleteOptionId">
            <x-slot name="title">{{ __('adminhub::components.option.delete_title') }}</x-slot>
            <x-slot name="content">
                <x-hub::alert level="danger">
                    {{ __('adminhub::components.option.delete_warning') }}
                </x-hub::alert>
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-between">
                    <x-hub::button theme="gray" wire:click="$set('deleteOptionId', null)" type="button">
                        {{ __('adminhub::global.cancel') }}
                    </x-hub::button>
                    <x-hub::button theme="danger" type="button" wire:click="deleteOption">
                        {{ __('adminhub::global.delete') }}
                    </x-hub::button>
                </div>
            </x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->optionValueToDelete)
        <x-hub::modal.dialog wire:model="deleteOptionValueId">
            <x-slot name="title">{{ __('adminhub::components.option.delete_option.value.title') }}</x-slot>
            <x-slot name="content">
                <x-hub::alert level="danger">
                    {{ __('adminhub::components.option.delete_option.value.warning') }}
                </x-hub::alert>
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-between">
                    <x-hub::button theme="gray" wire:click="$set('deleteOptionValueId', null)" type="button">
                        {{ __('adminhub::global.cancel') }}
                    </x-hub::button>
                    @if (!$this->optionValueToDelete->system)
                        <x-hub::button theme="danger" type="button" wire:click="deleteOptionValue">
                            {{ __('adminhub::global.delete') }}
                        </x-hub::button>
                    @endif
                </div>
            </x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->valueCreateOption)
        <x-hub::modal.dialog wire:model="valueCreateOptionId">
            <x-slot name="title">{{ __('adminhub::components.option.value.edit.create_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-options.value-edit', [
                    'option' => $this->valueCreateOption,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif
    @if ($this->optionValueToEdit)
        <x-hub::modal.dialog wire:model="editOptionValueId">
            <x-slot name="title">{{ __('adminhub::components.option.value.edit.update_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-options.value-edit', [
                    'optionValue' => $this->optionValueToEdit,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif
</div>
