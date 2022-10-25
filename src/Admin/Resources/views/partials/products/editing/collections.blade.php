<div class="shadow sm:rounded-md">
    <div class="flex-col space-y-4 rounded-md bg-white">
        <header class="flex items-center justify-between gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <div class="flex">
                <x-go-info-16 class="h-6 w-6 text-[#CFA55B]" />
                <span class="ml-2 text-sm font-semibold text-white">
                    {{ __('adminhub::partials.products.collections.heading') }}
                </span>
            </div>
            @livewire('hub.components.collection-search', [
                'existing' => $product->collections,
            ])
        </header>

        <div class="space-y-2 p-6">
            @foreach ($collections as $index => $collection)
                <div wire:key="collection_{{ $index }}">
                    <div class="flex items-center rounded border px-4 py-2 text-sm">
                        @if ($collection['thumbnail'])
                            <span class="mr-4 block w-12 flex-shrink-0">
                                <img src="{{ $collection['thumbnail'] }}" class="rounded">
                            </span>
                        @endif

                        <div class="flex grow">
                            <div class="flex grow flex-wrap items-center gap-1.5">
                                <strong class="rounded bg-blue-50 px-1.5 py-0.5 text-xs text-blue-600">
                                    {{ $collection['group_name'] }}
                                </strong>

                                @if (count($collection['breadcrumb']))
                                    <span class="flex items-center gap-1.5 text-gray-500">
                                        <span class="font-medium">
                                            {{ collect($collection['breadcrumb'])->first() }}
                                        </span>

                                        <x-hub::icon ref="chevron-right" class="h-4 w-4" style="solid" />
                                    </span>
                                @endif

                                @if (count($collection['breadcrumb']) > 1)
                                    <span class="flex items-center gap-1.5 text-gray-500"
                                        title="{{ collect($collection['breadcrumb'])->implode(' > ') }}">
                                        <span class="cursor-help font-medium">
                                            ...
                                        </span>

                                        <x-hub::icon ref="chevron-right" class="h-4 w-4" style="solid" />
                                    </span>
                                @endif

                                <strong class="max-w-[40ch] truncate text-gray-700" title="{{ $collection['name'] }}">
                                    {{ $collection['name'] }}
                                </strong>
                            </div>

                            <div class="flex items-center">
                                <x-hub::dropdown minimal>
                                    <x-slot name="options">
                                        <x-hub::dropdown.link
                                            class="flex items-center justify-between border-b px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                            :href="route('hub.collections.show', [
                                                'group' => $collection['group_id'],
                                                'collection' => $collection['id'],
                                            ])">
                                            {{ __('adminhub::partials.products.collections.view_collection') }}
                                        </x-hub::dropdown.link>

                                        <x-hub::dropdown.button
                                            wire:click.prevent="removeCollection({{ $index }})"
                                            class="flex items-center justify-between px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            {{ __('adminhub::global.remove') }}
                                        </x-hub::dropdown.button>
                                    </x-slot>
                                </x-hub::dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
