<div class="shadow sm:rounded-md">
    <div class="flex-col space-y-4 rounded-t-xl bg-white">
        <header class="flex items-center justify-between gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <div class="flex">
                <x-go-info-16 class="h-6 w-6 text-[#CFA55B]" />
                <span class="ml-2 text-sm font-semibold text-white">
                    {{ __('adminhub::partials.products.variants.heading') }}
                    <p class="text-sm text-gray-400">{{ __('adminhub::partials.products.variants.strapline') }}</p>
                </span>
            </div>
            <div>
                <x-hub::input.toggle wire:model="variantsEnabled" />
            </div>
        </header>
        @if ($this->getVariantsCount() <= 1)
            <div>
                @include('adminhub::partials.attributes', [
                    'attributeGroups' => $this->variantAttributeGroups,
                    'mapping' => 'variantAttributes',
                    'inline' => true,
                ])
            </div>
        @endif
        @if ($variantsEnabled)
            @if ($this->getVariantsCount() <= 1)
                @include('adminhub::partials.products.editing.options')
            @else
                <x-hub::table>
                    <x-slot name="head">
                        {{--                        <x-hub::table.heading>{{ __('Image') }}</x-hub::table.heading> --}}
                        <x-hub::table.heading>{{ __('adminhub::global.options') }}</x-hub::table.heading>
                        <x-hub::table.heading>{{ __('adminhub::global.sku') }}</x-hub::table.heading>
                        <x-hub::table.heading>{{ __('adminhub::global.unit_price_tax') }}</x-hub::table.heading>
                        <x-hub::table.heading>{{ __('adminhub::global.stock_incoming') }}</x-hub::table.heading>
                        <x-hub::table.heading></x-hub::table.heading>
                        <x-hub::table.heading></x-hub::table.heading>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($product->variants as $variant)
                            <x-hub::table.row>
                                {{--                                <x-hub::table.cell class="px-2 py-2"> --}}
                                {{--                                    <img src="{{ $variant->product->thumbnail->getUrl() }}" --}}
                                {{--                                        alt="{{ $variant->product->translateAttribute('name') }}"> --}}
                                {{--                                </x-hub::table.cell> --}}
                                <x-hub::table.cell class="w-full">
                                    @unless($variant->base)
                                        @foreach ($variant->values as $value)
                                            {{ $value->translate('name') }} {{ !$loop->last ? '/' : null }}
                                        @endforeach
                                    @else
                                        {{ $product->translateAttribute('name') }}
                                    @endunless
                                </x-hub::table.cell>
                                <x-hub::table.cell>
                                    {{ $variant->sku }}
                                </x-hub::table.cell>
                                <x-hub::table.cell>
                                    @php
                                        $price = $variant->basePrices->first(fn($price) => $price->currency->default);
                                    @endphp
                                    <span class="font-semibold">{{ $price?->price->formatted }}</span>
                                </x-hub::table.cell>
                                <x-hub::table.cell>
                                    {{ $variant->stock }} ({{ $variant->backorder }})
                                </x-hub::table.cell>
                                <x-hub::table.cell class="w-3">
                                    <a href="{{ route('hub.products.variants.show', [
                                        'product' => $product,
                                        'variant' => $variant,
                                    ]) }}"
                                        class="text-indigo-500 hover:underline">{{ __('adminhub::partials.products.variants.table_row_action_text') }}</a>
                                </x-hub::table.cell>
                                <x-hub::table.cell>
                                    @if ($variant->created_at == $variant->updated_at)
                                        <button class="text-red-600 hover:underline" type="button"
                                            wire:click.prevent="deleteVariant('{{ $variant->id }}')">
                                            {{ __('adminhub::partials.products.variants.table_row_delete_text') }}
                                        </button>
                                    @endif
                                </x-hub::table.cell>
                            </x-hub::table.row>
                        @endforeach
                    </x-slot>
                </x-hub::table>
            @endif
        @endif
    </div>
</div>
