<div class="space-y-4">
    <header class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white md:text-2xl">
            {{ __('adminhub::components.product.features.index.title') }}
        </h1>

        <div class="mt-4 sm:mt-0">
            <x-hub::button wire:click.prevent="$set('showFeatureCreate', true)">
                {{ __('adminhub::components.product.features.index.create_btn') }}
            </x-hub::button>
        </div>
    </header>

    <div wire:sort sort.options='{group: "groups", method: "sortGroups"}' class="space-y-2">
        @forelse($sortedProductFeatures as $feature)
            <div wire:key="group_{{ $feature->id }}" x-data="{ expanded: false }" sort.item="groups"
                sort.id="{{ $feature->id }}">
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
                            {{ $feature->translate('name') }}
                        </div>
                        <div class="flex">
                            @if ($feature->values->count())
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
                                        wire:click="$set('editFeatureId', {{ $feature->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        {{ __('adminhub::components.feature.edit_group_btn') }}
                                    </x-hub::dropdown.button>

                                    <x-hub::dropdown.button x-cloak
                                        wire:click="$set('valueCreateFeatureId', {{ $feature->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                        {{ __('adminhub::components.feature.create_feature_value') }}
                                    </x-hub::dropdown.button>

                                    <x-hub::dropdown.button x-cloak
                                        wire:click="$set('deleteFeatureId', {{ $feature->id }})"
                                        class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                        <span
                                            class="text-red-500">{{ __('adminhub::components.feature.delete_group_btn') }}</span>
                                    </x-hub::dropdown.button>
                                </x-slot>
                            </x-hub::dropdown>
                        </div>
                    </div>
                </div>
                <div x-cloak class="mt-2 ml-7 space-y-2 rounded border-l bg-opacity-5 py-4 pl-2 pr-4"
                    @if ($feature->values->count()) x-show="expanded" @endif>
                    <div class="space-y-2" wire:sort
                        sort.options='{group: "values", method: "sortFeatureValues", owner: {{ $feature->id }}}'
                        x-show="expanded">
                        @foreach ($feature->values as $featureValue)
                            <div class="sort-item-element flex w-full items-center justify-between rounded border border-transparent bg-white p-3 text-sm shadow-sm hover:border-gray-300"
                                wire:key="attribute_{{ $featureValue->id }}" sort.item="values"
                                sort.parent="{{ $feature->id }}" sort.id="{{ $featureValue->id }}">
                                <div sort.handle class="cursor-grab">
                                    <x-hub::icon ref="selector" style="solid"
                                        class="mr-2 text-gray-400 hover:text-gray-700" />
                                </div>
                                <span class="grow truncate">{{ $featureValue->translate('name') }}</span>
                                <div>
                                    <x-hub::dropdown minimal>
                                        <x-slot name="options">
                                            <x-hub::dropdown.button x-cloak type="button"
                                                wire:click="$set('editFeatureValueId', {{ $featureValue->id }})"
                                                class="flex items-center justify-between border-b px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                {{ __('adminhub::components.feature.edit_feature.value.btn') }}
                                                <x-hub::icon ref="pencil" style="solid" class="w-4" />
                                            </x-hub::dropdown.button>

                                            <x-hub::dropdown.button x-cloak
                                                wire:click="$set('deleteFeatureValueId', {{ $featureValue->id }})"
                                                class="flex items-center justify-between border-b px-4 py-2 text-sm hover:bg-gray-50">
                                                <span
                                                    class="text-red-500">{{ __('adminhub::components.feature.delete_feature.value.btn') }}</span>
                                            </x-hub::dropdown.button>
                                        </x-slot>
                                    </x-hub::dropdown>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (!$feature->values->count())
                        <span class="mx-4 text-sm text-gray-500">
                            {{ __('adminhub::components.product.features.no_feature_value_text') }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="w-full text-center text-gray-500">
                {{ __('adminhub::components.feature.no_groups') }}
            </div>
        @endforelse
    </div>

    <x-hub::modal.dialog wire:model="showFeatureCreate">
        <x-slot name="title">{{ __('adminhub::components.feature.create_title') }}</x-slot>
        <x-slot name="content">
            @livewire('hub.components.product-features.edit')
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-hub::modal.dialog>

    @if ($this->featureToEdit)
        <x-hub::modal.dialog wire:model="editFeatureId">
            <x-slot name="title">{{ __('adminhub::components.feature.edit_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-features.edit', [
                    'productFeature' => $this->featureToEdit,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->featureToDelete)
        <x-hub::modal.dialog wire:model="deleteFeatureId">
            <x-slot name="title">{{ __('adminhub::components.feature.delete_title') }}</x-slot>
            <x-slot name="content">
                <x-hub::alert level="danger">
                    {{ __('adminhub::components.feature.delete_warning') }}
                </x-hub::alert>
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-between">
                    <x-hub::button theme="gray" wire:click="$set('deleteFeatureId', null)" type="button">
                        {{ __('adminhub::global.cancel') }}
                    </x-hub::button>
                    <x-hub::button theme="danger" type="button" wire:click="deleteFeature">
                        {{ __('adminhub::global.delete') }}
                    </x-hub::button>
                </div>
            </x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->featureValueToDelete)
        <x-hub::modal.dialog wire:model="deleteFeatureValueId">
            <x-slot name="title">{{ __('adminhub::components.feature.delete_feature.value.title') }}</x-slot>
            <x-slot name="content">
                <x-hub::alert level="danger">
                    {{ __('adminhub::components.feature.delete_feature.value.warning') }}
                </x-hub::alert>
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-between">
                    <x-hub::button theme="gray" wire:click="$set('deleteFeatureValueId', null)" type="button">
                        {{ __('adminhub::global.cancel') }}
                    </x-hub::button>
                    @if (!$this->featureValueToDelete->system)
                        <x-hub::button theme="danger" type="button" wire:click="deleteFeatureValue">
                            {{ __('adminhub::global.delete') }}
                        </x-hub::button>
                    @endif
                </div>
            </x-slot>
        </x-hub::modal.dialog>
    @endif

    @if ($this->valueCreateFeature)
        <x-hub::modal.dialog wire:model="valueCreateFeatureId">
            <x-slot name="title">{{ __('adminhub::components.feature.value.edit.create_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-features.value-edit', [
                    'feature' => $this->valueCreateFeature,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif
    @if ($this->featureValueToEdit)
        <x-hub::modal.dialog wire:model="editFeatureValueId">
            <x-slot name="title">{{ __('adminhub::components.feature.value.edit.update_title') }}</x-slot>
            <x-slot name="content">
                @livewire('hub.components.product-features.value-edit', [
                    'featureValue' => $this->featureValueToEdit,
                ])
            </x-slot>
            <x-slot name="footer"></x-slot>
        </x-hub::modal.dialog>
    @endif
</div>
