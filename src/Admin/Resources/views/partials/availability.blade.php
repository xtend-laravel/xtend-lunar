<div>
    <div class="shadow sm:rounded-md">
        <div class="flex-col space-y-4 rounded-t-xl bg-white">
            <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                <x-go-info-16 class="h-6 w-6 text-[#CFA55B]" />
                <span class="ml-2 text-sm font-semibold text-white">
                    {{ __('adminhub::partials.availability.heading', [
                        'type' => $type ?? 'product',
                    ]) }}
                </span>
            </header>
            <x-hub::alert>
                {{ __('adminhub::partials.availability.schedule_notice', [
                    'type' => $type ?? 'product',
                ]) }}
            </x-hub::alert>
            <div class="space-y-4 p-6">
                <div class="space-y-4">
                    <header class="flex items-center justify-between">
                        <div>
                            <h3 class="text-md font-medium leading-6 text-gray-900">
                                {{ __('adminhub::partials.availability.channel_heading', [
                                    'type' => $type ?? 'product',
                                ]) }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ __('adminhub::partials.availability.channel_strapline', [
                                    'type' => $type ?? 'product',
                                ]) }}
                            </p>
                        </div>
                    </header>
                    <div class="divide divide-y">
                        @include('adminhub::partials.availability.channels')
                    </div>
                </div>
                @if ($customerGroups)
                    <div>
                        <header class="flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-medium leading-6 text-gray-900">
                                    {{ __('adminhub::partials.availability.customer_groups.title') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ __('adminhub::partials.availability.customer_groups.strapline', [
                                        'type' => $type ?? 'product',
                                    ]) }}
                                </p>
                            </div>
                        </header>
                        <div class="divide mt-4 divide-y">
                            @include('adminhub::partials.availability.customer-groups', [
                                'customerGroupType' => $customerGroupType ?? 'select',
                            ])
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>
