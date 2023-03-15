<div class="flex gap-x-2">
    @if ($this->transactions->count())
        <button
            class="inline-flex items-center rounded-3xl border border-transparent border-gray-200 bg-[#C3C3C3] px-4 py-2 text-sm font-bold text-white transition hover:border-gray-200 hover:bg-[#A3A3A3]"
            type="button" wire:click.prevent="$set('showRefund', true)">
            <x-heroicon-o-receipt-refund class="mr-2 w-4" />

            @if (count($this->selectedLines))
                {{ __('adminhub::components.orders.show.refund_lines_btn') }}
            @else
                {{ __('adminhub::components.orders.show.refund_btn') }}
            @endif

        </button>
    @endif

    @if ($this->requiresCapture)
        <button
            class="inline-flex items-center rounded border border-transparent border-gray-200 bg-gray-50 px-4 py-2 text-sm font-bold transition hover:border-gray-200 hover:bg-white"
            type="button" wire:click.prevent="$set('showCapture', true)">
            <x-hub::icon ref="credit-card" style="solid" class="mr-2 w-4" />
            {{ __('adminhub::components.orders.show.capture_payment_btn') }}
        </button>
    @endif

    <a class="inline-flex items-center rounded-3xl border border-transparent border-gray-200 bg-[#374151] px-4 py-2 text-sm font-bold text-white transition hover:border-gray-200 hover:bg-[#404348]"
        href="{{ route('hub.orders.pdf', $order->id) }}" target="_blank">
        <x-hub::icon ref="download" style="solid" class="mr-2 w-4" />

        {{ __('adminhub::components.orders.show.download_pdf') }}
    </a>

    <a class="inline-flex items-center rounded-3xl border border-transparent border-gray-200 bg-[#29AD61] px-4 py-2 text-sm font-bold text-white transition hover:border-gray-200 hover:bg-[#297061]"
        href="{{ route('hub.orders.pdf', $order->id) }}" target="_blank">
        <x-hub::icon ref="download" style="solid" class="mr-2 w-4" />

        {{ __('Shipping slip') }}
    </a>

</div>

<button
    class="inline-flex items-center rounded-3xl border border-transparent border-gray-200 bg-blue-500 px-4 py-2 text-sm font-bold text-white transition hover:border-gray-200 hover:bg-blue-700"
    type="button" wire:click.prevent="$set('showDiscount', true)">
    <x-heroicon-o-receipt-refund class="mr-2 w-4" />
    {{ __('Apply discount') }}
</button>

<div>

    @livewire('hub.components.orders.status', [
        'order' => $this->order,
    ])

    <div class="relative flex flex-1 justify-end" x-data="{ showMenu: false }">
        <x-hub::menu handle="order_actions">
            @if ($component->items->count())
                <button
                    class="inline-flex items-center rounded border bg-gray-50 px-4 py-2 font-bold transition hover:bg-white"
                    type="button" x-on:click="showMenu = !showMenu">
                    {{ __('adminhub::components.orders.show.more_actions_btn') }}

                    <x-hub::icon ref="chevron-down" style="solid" class="ml-2 w-4" />
                </button>

                <div class="absolute right-0 top-full z-50 mt-2 w-screen max-w-[200px] overflow-hidden rounded-lg border bg-white text-sm shadow-lg"
                    role="menu" x-on:click.away="showMenu = false" x-show="showMenu" x-transition x-cloak>

                    @foreach ($component->items as $item)
                        @if ($item->component)
                            @livewire($item->component, [
                                'order' => $this->order,
                            ])
                        @else
                            <x-hub::dropdown.link :route="route($item->route, $this->order->id)">
                                {{ $item->name }}
                            </x-hub::dropdown.link>
                        @endif
                    @endforeach

                </div>
            @endif
        </x-hub::menu>
    </div>
</div>
