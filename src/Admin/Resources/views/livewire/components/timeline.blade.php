<div>
    <div
        x-data="{}"
        @if($openModal)x-init="setTimeout(() => $dispatch('open-modal', { id: 'timeline-feed' }), 1000)"@endif
        x-on:click="$dispatch('open-modal', { id: 'timeline-feed' })"
        class="inline-block">
        <span class="relative inline-flex cursor-pointer">
            <button type="button">
                <x-entypo-chat class="h-8 w-8 text-white" />
            </button>
            @if ($this->totalActivityCount)
                <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-5 w-5">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
                    <span
                        class="relative inline-flex h-5 w-5 items-center justify-center rounded-full bg-sky-500 text-xs font-bold text-white">
                        {{ $this->totalActivityCount }}
                    </span>
                </span>
            @endif
        </span>
    </div>

    <x-adminhub::filament-modal id="timeline-feed" close-button slide-over width="md">
        <x-slot name="header">
            <div class="mt-4 flex items-center gap-4">
                <div class="shrink-0">
                    @livewire('hub.components.avatar')
                </div>

                <form class="relative w-full" wire:submit.prevent="addComment">
                    <textarea class="form-text h-[58px] w-full rounded-lg border border-gray-200 pl-4 pr-32 pt-5 sm:text-sm" type="text"
                        placeholder="Add a comment" wire:model.defer="comment" required multiline></textarea>

                    <button
                        class="absolute top-2 right-2 h-[42px] w-28 rounded-md border border-transparent bg-gray-100 text-xs font-bold leading-[42px] text-gray-700 hover:border-gray-100 hover:bg-gray-50"
                        type="submit">
                        <div wire:loading.remove wire:target="addComment">
                            Add Comment
                        </div>

                        <div wire:loading wire:target="addComment">
                            <x-hub::icon ref="refresh" style="solid" class="inline-block rotate-180 animate-spin" />
                        </div>
                    </button>
                </form>
            </div>
        </x-slot>

        @if ($this->activityLog->count())
            <div>
                <div class="relative -ml-[5px] pt-4">
                    <span class="absolute inset-y-0 left-5 w-[2px] rounded-full bg-gray-200"></span>

                    <div class="flow-root">
                        <ul class="-my-8 divide-y-2 divide-gray-200" role="list">
                            @foreach ($this->activityLog ?? [] as $log)
                                <li class="relative ml-5 py-8">
                                    <p class="ml-8 font-bold text-gray-900">
                                        {{ $log['date']->format('F jS, Y') }}
                                    </p>

                                    <ul class="mt-4 space-y-6">
                                        @foreach ($log['items'] as $item)
                                            <li class="relative pl-8">
                                                <div @class([
                                                    'absolute top-[2px]',
                                                    '-left-[calc(0.75rem_-_1px)]' => $item['log']->causer,
                                                    '-left-[calc(0.5rem_-_1px)]' => !$item['log']->causer,
                                                ])>
                                                    @if ($item['log']->causer)
                                                        <x-hub::gravatar :email="$item['log']->causer->email"
                                                            class="h-6 w-6 rounded-full ring-4 ring-gray-200" />
                                                    @else
                                                        <span @class([
                                                            'absolute w-4 h-4 rounded-full ring-4 bg-gray-300 ring-gray-200',
                                                            '!bg-blue-500 !ring-blue-100' => $item['log']->description == 'created',
                                                            '!bg-purple-500 !ring-purple-100' =>
                                                                $item['log']->description == 'status-update',
                                                            '!bg-teal-500 !ring-teal-100' => $item['log']->description == 'updated',
                                                        ])>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div @class([
                                                    'flex justify-between',
                                                    'pt-[5px]' => $item['log']->causer,
                                                    'pt-[1px]' => !$item['log']->causer,
                                                ])>
                                                    <div>
                                                        <div class="text-xs font-medium text-gray-500">
                                                            @if (!$item['log']->causer)
                                                                {{ __('adminhub::components.activity-log.system') }}
                                                            @else
                                                                {{ $item['log']->causer->fullName ?: $item['log']->causer->name }}
                                                            @endif
                                                        </div>

                                                        @if (count($item['renderers']))
                                                            <div class="mt-2 text-sm font-medium text-gray-700">
                                                                @foreach ($item['renderers'] as $render)
                                                                    {!! $render !!}
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <time
                                                        class="ml-4 mt-0.5 flex-shrink-0 text-xs font-medium text-gray-500">
                                                        {{ $item['log']->created_at->format('h:ia') }}
                                                    </time>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div @class([
                'flex flex-col items-center justify-center mx-auto my-6 space-y-4 text-center bg-white',
                'dark:bg-gray-800' => config('notifications.dark_mode'),
            ])>
                <div @class([
                    'flex items-center justify-center w-12 h-12 text-primary-500 rounded-full bg-primary-50',
                    'dark:bg-gray-700' => config('notifications.dark_mode'),
                ])>
                    <x-far-message class="h-5 w-5" />
                </div>

                <div class="max-w-md space-y-1">
                    <h2 @class([
                        'text-lg font-bold tracking-tight',
                        'dark:text-white' => config('notifications.dark_mode'),
                    ])>
                        {{ __('No messages for this [Model]') }}
                    </h2>

                    <p @class([
                        'whitespace-normal text-sm font-medium text-gray-500',
                        'dark:text-gray-400' => config('notifications.dark_mode'),
                    ])>
                        {{ __('notifications::database.modal.empty.description') }}
                    </p>
                </div>
            </div>
        @endif
    </x-adminhub::filament-modal>
</div>
