<li>
    <div class="flex items-start items-center">
        <div class="flex-1">
            <div class="relative flex items-center justify-between gap-4 pl-8 text-xs xl:order-last xl:justify-end xl:pl-0"
                x-data="{ showMenu: false }">
                <div class="w-16">
                    <span>Price unit</span>
                </div>

                <div class="w-16">
                    <span>Quantity</span>
                </div>

                <div class="w-16">
                    <span>Refunded</span>
                </div>

                <div class="w-16">
                    Total
                </div>
            </div>
        </div>
    </div>
</li>

@foreach ($this->visibleLines as $line)
    <li class="py-3" x-data="{ showDetails: false }">
        <div class="flex items-start items-center">
            <div class="flex items-center gap-2">
                @if ($this->transactions->count())
                    <x-hub::input.checkbox value="{{ $line->id }}" wire:model="selectedLines" />
                @endif
                <div class="flex-shrink-0 items-center overflow-hidden rounded border border-gray-100 p-1">
                @if($thumbnail = $line->purchasable->getThumbnail())
                    <img
                        class="object-contain h-32 w-32"
                        src="{{ $thumbnail->getUrl('small') }}"
                      />
                    @else
                        <x-hub::icon ref="photograph"
                         class="w-20 h-20 lt-text-gray-300 flex items-center m-auto" />
                    @endif
                </div>
            </div>

            <div class="flex-1">
                <div class="gap-8 xl:flex xl:items-start xl:justify-between">
                    <div class="relative flex items-center justify-between gap-4 pl-8 xl:order-last xl:justify-end xl:pl-0"
                        x-data="{ showMenu: false }">

                        <div class="w-16">
                            <span>---</span>
                        </div>

                        <div class="w-16">
                            <span>{{ $line->quantity }}</span>
                        </div>

                        <div class="w-16">
                            <span>---</span>
                        </div>

                        <p class="w-16 text-sm font-medium text-gray-700">
                            {{ $line->unit_price->formatted }}

                            @if ($line->sub_total->value !== $line->unit_price->value)
                                <span class="ml-1">
                                    {{ $line->sub_total->formatted }}
                                </span>
                            @endif
                        </p>

                        {{-- <button
              class="text-gray-400 hover:text-gray-500"
              x-on:click="showMenu = !showMenu"
              type="button"
            >
              <x-hub::icon
                ref="dots-vertical"
                style="solid"
              />
            </button> --}}

                        {{-- <div
              class="absolute right-0 z-50 mt-2 text-sm bg-white border rounded-lg shadow-lg top-full"
              role="menu"
              x-on:click.away="showMenu = false"
              x-show="showMenu"
              x-transition
              x-cloak
            >
              <div
                class="py-1"
                role="none"
              >
                <button
                  class="w-full px-4 py-2 text-left transition hover:bg-white"
                  role="menuitem"
                  type="button"
                >
                  Refund Line
                </button>

                <button
                  class="w-full px-4 py-2 text-left transition hover:bg-white"
                  role="menuitem"
                  type="button"
                >
                  Refund Stock
                </button>
              </div>
            </div> --}}
                    </div>

                    <button class="group mt-2 flex items-center xl:mt-0" x-on:click="showDetails = !showDetails"
                        type="button">
                        <div class="transition-transform"
                            :class="{
                                '-rotate-90 ': !showDetails
                            }">
                            <x-hub::icon ref="chevron-down" style="solid"
                                class="mx-1 -mt-7 w-6 text-gray-400 group-hover:text-gray-500 xl:mt-0" />
                        </div>
                        <div class="max-w-sm space-y-2 text-left">
                            <x-hub::tooltip :text="$line->description" :left="true">
                                <p class="truncate text-sm font-bold leading-tight text-gray-800">
                                    {{ $line->description }}
                                </p>
                            </x-hub::tooltip>

                            <div class="flex text-xs font-medium text-gray-600">
                                <p>{{ $line->identifier }}</p>

                                @if ($line->purchasable->getOptions()->count())
                                    <dl class="flex space-x-3 before:mx-3 before:text-gray-200 before:content-['|']">
                                        @foreach ($line->purchasable->getOptions() as $option)
                                            <div class="flex gap-0.5">
                                                <dt>{{ $option }}</dt>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endif
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div class="pl-24 text-gray-700" x-show="showDetails">
            @if (!is_null($line->purchasable?->stock))
                <span class="mt-2 block rounded border p-2 text-xs">
                    <span @class([
                        'text-red-500' => $line->purchasable->stock < 50,
                        'text-green-500' => $line->purchasable->stock > 50,
                    ])>
                        {{ __('adminhub::partials.orders.lines.current_stock_level', [
                            'count' => $line->purchasable->stock,
                        ]) }}
                    </span>
                    @if (!is_null($line->meta?->stock_level ?? null))
                        ({{ __('adminhub::partials.orders.lines.purchase_stock_level', [
                            'count' => $line->meta->stock_level,
                        ]) }})
                    @endif
                </span>
            @endif
            <div class="mt-4 space-y-4">
                <article class="text-sm">
                    <p>
                        <strong>{{ __('adminhub::global.notes') }}:</strong>

                        {{ $line->notes }}
                    </p>
                </article>

                <div class="overflow-hidden overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                    {{ __('adminhub::partials.orders.lines.unit_price') }}
                                </td>

                                <td class="whitespace-nowrap p-2 text-gray-700">
                                    {{ $line->unit_price->formatted }} / {{ $line->unit_quantity }}
                                </td>
                            </tr>

                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                    {{ __('adminhub::partials.orders.lines.quantity') }}
                                </td>

                                <td class="whitespace-nowrap p-2 text-gray-700">
                                    {{ $line->quantity }}
                                </td>
                            </tr>

                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                    {{ __('adminhub::partials.orders.lines.sub_total') }}
                                </td>

                                <td class="whitespace-nowrap p-2 text-gray-700">
                                    {{ $line->sub_total->formatted }}
                                </td>
                            </tr>

                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                    {{ __('adminhub::partials.orders.lines.discount_total') }}
                                </td>

                                <td class="whitespace-nowrap p-2 text-gray-700">
                                    {{ $line->discount_total->formatted }}
                                </td>
                            </tr>

                            @foreach ($line->tax_breakdown as $tax)
                                <tr class="divide-x divide-gray-200">
                                    <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                        {{ $tax->description }}
                                    </td>

                                    <td class="whitespace-nowrap p-2 text-gray-700">
                                        {{ $tax->total->formatted }}
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap p-2 font-medium text-gray-900">
                                    {{ __('adminhub::partials.orders.lines.total') }}
                                </td>

                                <td class="whitespace-nowrap p-2 text-gray-700">
                                    {{ $line->total->formatted }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </li>
@endforeach
