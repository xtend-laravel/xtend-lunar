<section>
    <header class="flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-900 md:text-2xl">
            <span class="text-gray-500">
                {{ __('adminhub::components.orders.show.title') }}
            </span>

            <span class="text-[#CFA55B]">#{{ $order->id }}</span> {{ $order->reference }}
        </h1>
        <div class="flex items-center gap-x-4">
            <div
                class="block items-center rounded-3xl border border-transparent border-gray-200 bg-[#28AE61] px-4 py-2 text-sm font-bold text-white transition">
                <span>{{ $order->total->formatted }}</span>
            </div>
            <div class="font-semibold">
                @if ($order->placed_at)
                    {{ $order->placed_at->format('d-m-Y h:ia') }}
                @else
                    -
                @endif
            </div>
        </div>

    </header>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-start">
        <div class="lg:col-span-2">
            @if ($this->requiresCapture)
                <div class="mb-4">
                    <x-hub::alert level="danger">
                        {{ __('adminhub::components.orders.show.requires_capture') }}
                    </x-hub::alert>
                </div>
            @endif

            <div class="mt-0">
                @if ($this->paymentStatus == 'partial-refund')
                    <div class="rounded border border-blue-500">
                        <x-hub::alert>
                            {{ __('adminhub::components.orders.show.partially_refunded') }}
                        </x-hub::alert>
                    </div>
                @endif

                @if ($this->paymentStatus == 'refunded')
                    <div class="rounded border border-red-500">
                        <x-hub::alert level="danger">
                            {{ __('adminhub::components.orders.show.refunded') }}
                        </x-hub::alert>
                    </div>
                @endif
            </div>

            <div class="mt-4 rounded-lg bg-white p-6 shadow">
                <div class="flow-root">

                    <div class="mb-10 flex items-center justify-between space-x-2">
                        @include('adminhub::partials.orders.actions')
                    </div>

                    <ul class="divide-y">
                        @include('adminhub::partials.orders.lines')
                    </ul>
                </div>

                @if ($this->physicalAndDigitalLines->count() > $maxLines)
                    <div class="mt-4 text-center">
                        @if (!$allLinesVisible)
                            <div class="relative">
                                <hr class="border-b-1 transparent absolute top-3 block w-full border-red-200" />

                                <div class="relative">
                                    <span class="bg-white px-2 text-xs font-medium text-red-600">
                                        {{ __('adminhub::components.orders.show.additional_lines_text', [
                                            'count' => $this->physicalAndDigitalLines->count() - $maxLines,
                                        ]) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <button class="mt-1 rounded border px-3 py-1 text-xs text-blue-800 shadow-sm"
                            wire:click="$set('allLinesVisible', {{ !$allLinesVisible }})" type="button">
                            @if (!$allLinesVisible)
                                {{ __('adminhub::components.orders.show.show_all_lines_btn') }}
                            @else
                                {{ __('adminhub::components.orders.show.collapse_lines_btn') }}
                            @endif
                        </button>
                    </div>
                @endif

                <div class="mt-8">
                    @include('adminhub::partials.orders.totals')
                </div>
            </div>

            <div class="mt-4 flex gap-4">
                <div class="mt-4 flex w-1/2 flex-col rounded-t-xl bg-white">
                    <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                        <x-gmdi-monetization-on-o class="h-6 w-6 text-[#CFA55B]" />
                        <span class="ml-1 text-sm font-semibold text-white">{{ __('Payment Details') }}</span>
                    </header>

                    @include('adminhub::partials.orders.transactions')
                </div>

                <div class="mt-4 flex w-1/2 flex-col rounded-t-xl bg-white">
                    <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                        <x-gmdi-local-shipping-o class="h-6 w-6 text-[#CFA55B]" />
                        <span class="ml-1 text-sm font-semibold text-white">{{ __('Shipping Details') }}</span>
                    </header>
                    <div class="p-4">
                        <div class="w-full overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="text-left text-xs">
                                    <tr>
                                        <th>Transporteur</th>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th>Weight</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>

                                <tbody class="relative text-sm">
                                    <tr>
                                        <td>UPS Access Point ™</td>
                                        <td>13/09/2022</td>
                                        <td>€0,00</td>
                                        <td>2kg</td>
                                        <td>--</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <address class="mt-4 text-sm not-italic text-gray-600">
                                @if ($this->shippingAddress->company_name)
                                    {{ $this->shippingAddress->company_name }} <br>
                                @endif
                                {{ $this->shippingAddress->fullName }} <br>
                                {{ $this->shippingAddress->line_one }} <br>

                                @if ($this->shippingAddress->line_two)
                                    {{ $this->shippingAddress->line_two }} <br>
                                @endif

                                @if ($this->shippingAddress->line_three)
                                    {{ $this->shippingAddress->line_three }} <br>
                                @endif

                                @if ($this->shippingAddress->city)
                                    {{ $this->shippingAddress->city }} <br>
                                @endif

                                @if ($this->shippingAddress->state)
                                    {{ $this->shippingAddress->state }} <br>
                                @endif

                                {{ $this->shippingAddress->postcode }} <br>

                                {{ $this->shippingAddress->country?->name }}

                                <div class="mt-2">
                                    <div class="flex items-center">
                                        <x-hub::icon ref="phone" class="mr-2 w-4" />
                                        @if ($this->shippingAddress->contact_phone)
                                            <a href="tel:{{ $this->shippingAddress->contact_phone }}"
                                                class="text-blue-600 underline">{{ $this->shippingAddress->contact_phone }}</a>
                                        @else
                                            <span
                                                class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center">
                                        <x-hub::icon ref="mail" class="mr-2 w-4" />
                                        @if ($this->shippingAddress->contact_email)
                                            <a href="mailto:{{ $this->shippingAddress->contact_email }}"
                                                class="text-blue-600 underline">{{ $this->shippingAddress->contact_email }}</a>
                                        @else
                                            <span
                                                class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            {{--            <div class="mt-4"> --}}
            {{--                <header class="my-6 font-medium"> --}}
            {{--                    {{ __('adminhub::components.orders.show.timeline_header') }} --}}
            {{--                </header> --}}

            {{--                @livewire('hub.components.activity-log-feed', [ --}}
            {{--                    'subject' => $this->order, --}}
            {{--                ]) --}}
            {{--            </div> --}}
        </div>

        <div class="space-y-4">

            @foreach ($this->getSlotsByPosition('top') as $slot)
                <div id="{{ $slot->handle }}">
                    <div>@livewire($slot->component, ['slotModel' => $order], key('top-slot-{{ $slot->handle }}'))</div>
                </div>
            @endforeach

            <div class="mt-4 rounded-t-xl bg-white">
                <header class="flex items-center justify-between gap-1 rounded-t-xl bg-[#353F4F] p-3">
                    <div class="flex items-center gap-1">
                        <x-heroicon-s-user-circle class="h-6 w-6 text-[#CFA55B]" />
                        <span class="ml-1 text-sm font-semibold text-white">{{ __('Customer Details') }}</span>
                    </div>
                    <a class="ml-4 flex-shrink-0 rounded border bg-gray-50 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-white"
                        href="{{ route('hub.customers.show', $order->customer) }}">
                        {{ __('adminhub::components.orders.show.view_customer') }}
                    </a>
                </header>

                <div class="p-0">
                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">{{ __('Customer') }}</dt>
                        <dd class="text-right">{{ $order->customer->fullName }}</dd>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">{{ __('Email') }}</dt>
                        <dd class="text-right">{{ $order->customer->email }}</dd>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">{{ __('Validated orders') }}</dt>
                        <dd class="text-right">{{ $this->ordersValidatedCount ?? 00 }}</dd>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">{{ __('Total spent since registration') }}</dt>
                        <dd class="text-right">{{ $this->totalSpend->formatted ?? 00 }}</dd>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-2 px-4 py-3">
                        <dt class="font-medium text-gray-500">{{ __('Account registered') }}</dt>
                        <dd class="text-right">{{ $order->customer->created_at->format('jS F Y h:ma') }}</dd>
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-t-xl bg-white">
                <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                    <x-tabler-map-2 class="h-6 w-6 text-[#CFA55B]" />
                    <span class="ml-1 text-sm font-semibold text-white">{{ __('Customer Addresses') }}</span>
                </header>

                <div class="flex bg-white">
                    <div class="rounded-lg bg-white p-4 shadow">
                        @livewire(
                            'hub.components.orders.address',
                            [
                                'order' => $order,
                                'addressesOptions' => $this->addressesOptions,
                                'type' => 'shipping',
                                'heading' => __('adminhub::components.orders.show.shipping_header'),
                            ],
                            key('shipping-address-{{ $order->id }}'),
                        )
                    </div>

                    <div class="rounded-lg bg-white p-4 shadow">
                        @livewire(
                            'hub.components.orders.address',
                            [
                                'order' => $order,
                                'addressesOptions' => $this->addressesOptions,
                                'type' => 'billing',
                                'heading' => __('adminhub::components.orders.show.billing_header'),
                                'message' => __('adminhub::components.orders.show.billing_matches_shipping'),
                            ],
                            key('billing-address-{{ $order->id }}'),
                        )
                    </div>
                </div>
            </div>

            <section class="mt-4 rounded-t-xl bg-white">
                <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                    <x-akar-info class="h-6 w-6 text-[#CFA55B]" />
                    <span
                        class="ml-1 text-sm font-semibold text-white">{{ __('adminhub::components.orders.show.additional_fields_header') }}</span>
                </header>

                <div class="h-20"></div>

                <dl class="mt-4 space-y-2 text-sm text-gray-600">
                    @foreach ($this->metaFields as $key => $value)
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="font-medium text-gray-700">
                                {{ $key }}:
                            </dt>

                            <dd class="col-span-2">
                                @if (!is_string($value))
                                    <pre class="font-mono">{{ json_encode($value) }}</pre>
                                @else
                                    {{ $value }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            @foreach ($this->getSlotsByPosition('bottom') as $slot)
                <div id="{{ $slot->handle }}">
                    <div>@livewire($slot->component, ['slotModel' => $order], key('bottom-slot-{{ $slot->handle }}'))</div>
                </div>
            @endforeach
        </div>

        <x-hub::modal.dialog form="updateStatus" wire:model="showUpdateStatus">
            <x-slot name="title">
                {{ __('adminhub::orders.update_status.title') }}
            </x-slot>

            <x-slot name="content">
                <x-hub::input.group :label="__('adminhub::inputs.status.label')" for="status" required :error="$errors->first('status')">
                    <x-hub::input.select wire:model.defer="order.status" required>
                        @foreach ($this->statuses as $handle => $status)
                            <option value="{{ $handle }}">{{ $status['label'] }}</option>
                        @endforeach
                    </x-hub::input.select>
                </x-hub::input.group>
            </x-slot>

            <x-slot name="footer">
                <x-hub::button type="button" wire:click.prevent="$set('showUpdateStatus', false)" theme="gray">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button type="submit">
                    {{ __('adminhub::orders.update_status.btn') }}
                </x-hub::button>
            </x-slot>
        </x-hub::modal.dialog>

        <x-hub::modal wire:model="showRefund">
            <div class="p-4">
                @livewire('hub.components.orders.refund', [
                    'order' => $this->order,
                    'amount' => $this->refundAmount / 100,
                ])
            </div>
        </x-hub::modal>

        <x-hub::modal wire:model="showDiscount">
            <div class="p-4">
                @livewire('hub.components.orders.discount', [
                    'order' => $this->order,
                ])
            </div>
        </x-hub::modal>

        <x-hub::modal wire:model="showCapture">
            <div class="p-4">
                @livewire('hub.components.orders.capture', [
                    'order' => $this->order,
                    'amount' => $this->order->total->decimal,
                ])
            </div>
        </x-hub::modal>

        <x-hub::slideover wire:model="showShippingAddressEdit" form="saveShippingAddress">
            @include('adminhub::partials.forms.address', [
                'bind' => 'shippingAddress',
                'states' => $this->shippingStates,
            ])

            <x-slot name="footer">
                <x-hub::button wire:click.prevent="$set('showShippingAddressEdit', false)" theme="gray">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button type="submit">
                    {{ __('adminhub::components.orders.show.save_shipping_btn') }}
                </x-hub::button>
            </x-slot>
        </x-hub::slideover>

        <x-hub::slideover wire:model="showBillingAddressEdit" form="saveBillingAddress">
            @include('adminhub::partials.forms.address', [
                'bind' => 'billingAddress',
                'states' => $this->billingStates,
            ])

            <x-slot name="footer">
                <x-hub::button wire:click.prevent="$set('showBillingAddressEdit', false)" theme="gray">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button type="submit">
                    {{ __('adminhub::components.orders.show.save_billing_btn') }}
                </x-hub::button>
            </x-slot>
        </x-hub::slideover>
    </div>
</section>
