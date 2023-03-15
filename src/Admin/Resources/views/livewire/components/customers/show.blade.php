<div class="flex-col space-y-4">
    <div class="flex-none items-center justify-between sm:flex">
        <strong class="text-lg font-bold md:text-2xl">
            {{ $customer->fullName }}
        </strong>
        <div class="px-0 py-3 sm:px-4">
            <span class="font-medium text-gray-500">
                Last updated:
                <span class="font-bold">{{ $customer->updated_at }}</span>
            </span>
        </div>
    </div>

    <div class="gap-x-4 space-y-4 xl:flex xl:flex-row-reverse xl:space-y-0">
        <div class="space-y-4 xl:w-1/3">

            <div class="rounded-t-xl bg-white">

                @foreach ($this->getSlotsByPosition('top') as $slot)
                    <div id="{{ $slot->handle }}">
                        <div>
                            @livewire($slot->component, ['slotModel' => $customer], key('top-slot-' . $slot->handle))
                        </div>
                    </div>
                @endforeach

                <header class="flex items-center justify-between rounded-t-xl bg-[#353F4F] p-3">
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-s-user-circle class="h-6 w-6 text-[#CFA55B]" />
                        <span class="ml-1 text-sm font-semibold text-white">{{ __('Customer Details') }}</span>
                    </div>
                    <button
                        class="ml-8 rounded border border-transparent bg-gray-100 px-4 py-2 text-xs font-bold text-gray-700 hover:border-gray-100 hover:bg-gray-50"
                        type="button" wire:click.prevent="triggerForm('customer-detail-form', 'slideover')">
                        {{ __('adminhub::global.edit') }}
                    </button>
                </header>
                <dl class="text-sm text-gray-600">

                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Customer</dt>
                        <dd class="break-words text-left font-bold">{{ $customer->title }} {{ $customer->fullName }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Age</dt>
                        @if ($customer->dob)
                            <dd class="text-left font-bold">{{ $customer->dob->age }} years old
                                ({{ $customer->dob->format('jS F Y') }})
                            </dd>
                        @else
                            <dd class="text-left font-bold">--</dd>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="break-words text-left font-bold">{{ $customer->email ?: '--' }}</dd>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Language</dt>
                        <dd class="text-left font-bold">{{ $customer->language?->name ?: '--' }}</dd>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Registration date</dt>
                        <dd class="text-left font-bold">
                            {{ $customer->created_at->format('jS F Y h:ma') }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Enabled</dt>
                        <dd class="text-left font-bold">
                            @if ($customer->is_active)
                                <x-heroicon-o-check-circle class="h-6 w-6 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="h-6 w-6 text-danger-500" />
                            @endif
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Newsletter</dt>
                        <dd class="text-left font-bold">
                            @if ($customer->newsletter)
                                <x-heroicon-o-check-circle class="h-6 w-6 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="h-6 w-6 text-danger-500" />
                            @endif
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 items-center gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Company</dt>
                        <dd class="text-left font-bold">
                            {{ $customer->company_name ?: '--' }}
                            @if ($customer->vat_no)
                                ({{ $customer->vat_no }})
                            @endif
                        </dd>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-b px-4 py-3">
                        <dt class="font-medium text-gray-500">Groups</dt>
                        <dd class="flex gap-x-2 text-left font-bold">
                            @foreach ($customer->customerGroups as $group)
                                <!-- @todo Add color to group -->
                                <span class="inline-block rounded px-2 py-1 text-center text-xs text-white"
                                    style="background: #8a2be2;">
                                    <span class="">{{ $group->translate('name') }}</span>
                                </span>
                            @endforeach
                        </dd>
                    </div>

                    <div id="attributes">
                        @include('adminhub::partials.attributes', ['inline' => true])
                    </div>

                </dl>

                @foreach ($this->getSlotsByPosition('bottom') as $slot)
                    <div id="{{ $slot->handle }}">
                        <div>
                            @livewire($slot->component, ['slotModel' => $customer], key('top-slot-' . $slot->handle))
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="space-y-4 xl:w-2/3">
            <div>
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500">
                            {{ __('adminhub::components.customers.show.metrics.total_orders') }}
                        </dt>

                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->ordersCount }}</dd>
                    </div>

                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500">
                            {{ __('adminhub::components.customers.show.metrics.avg_spend') }}
                        </dt>

                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->avgSpend->formatted }}</dd>
                    </div>

                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500">
                            {{ __('adminhub::components.customers.show.metrics.total_spend') }}
                        </dt>

                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->totalSpend->formatted }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded bg-white shadow">
                <header class="border-b px-4 py-4 font-bold">
                    {{ __('adminhub::components.customers.show.year_spending') }}
                </header>

                <div class="h-80 p-4">
                    @livewire('hub.components.reporting.apex-chart', ['options' => $this->spendingChart])
                </div>
            </div>
        </div>
    </div>

    <div>
        <div x-data="{ tab: 'order_history' }">
            <div>
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">

                        <button type="button" x-on:click.prevent="tab = 'order_history'"
                            class="rounded-md px-3 py-2 text-sm font-medium"
                            :class="{
                                'bg-white shadow': tab == 'order_history',
                                'hover:text-gray-700 text-gray-500': tab != 'order_history'
                            }">
                            {{ __('adminhub::components.customers.show.order_history') }}
                        </button>

                        <button type="button" x-on:click.prevent="tab = 'purchase_history'"
                            class="rounded-md px-3 py-2 text-sm font-medium"
                            :class="{
                                'bg-white shadow': tab == 'purchase_history',
                                'hover:text-gray-700 text-gray-500': tab != 'purchase_history'
                            }">
                            {{ __('adminhub::components.customers.show.purchase_history') }}
                        </button>

                        <button type="button" x-on:click.prevent="tab = 'users'"
                            class="rounded-md px-3 py-2 text-sm font-medium"
                            :class="{
                                'bg-white shadow': tab == 'users',
                                'hover:text-gray-700 text-gray-500': tab != 'users'
                            }">
                            {{ __('adminhub::components.customers.show.users') }}
                        </button>

                        <a href="#" x-on:click.prevent="tab = 'addresses'"
                            class="rounded-md px-3 py-2 text-sm font-medium"
                            :class="{
                                'bg-white shadow': tab == 'addresses',
                                'hover:text-gray-700 text-gray-500': tab != 'addresses'
                            }">
                            {{ __('adminhub::components.customers.show.addresses') }}
                        </a>
                    </nav>
                </div>
            </div>

            <div x-show="tab == 'order_history'" class="mt-4">
                @if (!$this->orders->count())
                    <div class="mt-12 w-full text-center text-sm text-gray-500">
                        {{ __('adminhub::components.customers.show.no_order_history') }}
                    </div>
                @else
                    @livewire('hub.components.orders.table', [
                        'searchable' => false,
                        'canSaveSearches' => false,
                        'filterable' => false,
                        'customerId' => $this->customer->id,
                    ])
                @endif
            </div>

            <div x-show="tab == 'purchase_history'" class="mt-4">
                @if (!$this->purchaseHistory->count())
                    <div class="mt-12 w-full text-center text-sm text-gray-500">
                        {{ __('adminhub::components.customers.show.no_purchase_history') }}
                    </div>
                @else
                    @include('adminhub::partials.customers.purchase-history')
                @endif
            </div>

            <div x-show="tab == 'users'" class="mt-4">
                @if (!$this->users->count())
                    <div class="mt-12 w-full text-center text-sm text-gray-500">
                        {{ __('adminhub::components.customers.show.no_users') }}
                    </div>
                @else
                    @include('adminhub::partials.customers.users')
                @endif
            </div>

            <div x-show="tab == 'addresses'" class="mt-4">
                @if (!$this->addresses->count())
                    <div class="mt-12 w-full text-center text-sm text-gray-500">
                        {{ __('adminhub::components.customers.show.no_addresses') }}
                    </div>
                @else
                    @include('adminhub::partials.customers.addresses')
                @endif
            </div>
        </div>
    </div>

    <div class="ui-sideover-forms">
        @each('adminhub::partials.ui.slideover', $this->slideoverForms ?? [], 'slideoverForm')
    </div>

    <div class="ui-modal-forms">
        @each('adminhub::partials.ui.modal', $this->modalForms ?? [], 'modalForm')
    </div>

    <x-hub::slideover wire:model="addressIdToEdit" form="saveAddress">
        @include('adminhub::partials.forms.address', [
            'bind' => 'address',
            'states' => $this->states,
        ])

        <div class="mt-4 flex justify-between">
            <x-hub::input.group label="Billing Default" for="billing_default">
                <x-hub::input.toggle wire:model.defer="address.billing_default" />
            </x-hub::input.group>

            <x-hub::input.group label="Shipping Default" for="shipping_default">
                <x-hub::input.toggle wire:model.defer="address.shipping_default" />
            </x-hub::input.group>
        </div>

        <x-slot name="footer">
            <x-hub::button wire:click.prevent="$set('addressIdToEdit', null)" theme="gray">
                {{ __('adminhub::global.cancel') }}
            </x-hub::button>

            <x-hub::button type="submit">
                {{ __('adminhub::components.orders.show.save_shipping_btn') }}
            </x-hub::button>
        </x-slot>
    </x-hub::slideover>

    <x-hub::modal.dialog form="removeAddress" wire:model="addressToRemove">
        <x-slot name="title">
            {{ __('adminhub::components.customers.show.remove_address.title') }}
        </x-slot>

        <x-slot name="content">
            <x-hub::alert level="warning">
                {{ __('adminhub::components.customers.show.remove_address.confirm') }}
            </x-hub::alert>
        </x-slot>

        <x-slot name="footer">
            <x-hub::button type="button" wire:click.prevent="$set('addressToRemove', null)" theme="gray">
                {{ __('adminhub::global.cancel') }}
            </x-hub::button>

            <x-hub::button type="submit">
                {{ __('adminhub::components.customers.show.remove_address_btn') }}
            </x-hub::button>
        </x-slot>
    </x-hub::modal.dialog>
</div>
