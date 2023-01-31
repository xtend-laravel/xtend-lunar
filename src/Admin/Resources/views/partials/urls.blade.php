<div class="overflow-hidden shadow sm:rounded-md">
    <div class="flex-col space-y-4 bg-white">
        <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <x-go-info-16 class="h-6 w-6 text-[#CFA55B]" />
            <span class="ml-2 text-sm font-semibold text-white">{{ __('adminhub::partials.urls.title') }}</span>
        </header>

        @if ($errors->has('urls'))
            <x-hub::alert level="danger">
                {{ $errors->first('urls') }}
            </x-hub::alert>
        @endif

        <div class="space-y-4 p-6">
            @if (count($urls))
                <div>
                    <div class="flex items-center space-x-4 text-sm font-medium text-gray-700">
                        <div class="w-64">{{ __('adminhub::global.language') }}</div>
                        <div class="w-full">{{ __('adminhub::global.slug') }}</div>
                        <div class="w-32">{{ __('adminhub::global.default') }}</div>
                    </div>
                </div>
            @endif

            @foreach ($urls as $index => $url)
                <div wire:key="url_{{ $url['key'] }}">
                    <div class="flex items-center space-x-4">
                        <div class="w-64">
                            <x-hub::input.select wire:model.defer="urls.{{ $index }}.language_id">
                                @foreach ($this->languages as $lang)
                                    <option value="{{ $lang['id'] }}">{{ $lang['name'] }}</option>
                                @endforeach
                            </x-hub::input.select>
                        </div>

                        <div class="w-full">
                            <x-hub::input.text wire:model.defer="urls.{{ $index }}.slug" />
                        </div>

                        <div class="flex items-center w-32 space-x-4">
                            <x-hub::input.toggle wire:model.defer="urls.{{ $index }}.default" />

                            <button class="text-gray-400" wire:click.prevent="removeUrl('{{ $index }}')">
                                <x-hub::icon ref="trash" style="solid" />
                            </button>
                        </div>
                    </div>
                </div>
                @if ($errors->has("urls.{$loop->index}.*"))
                    <div class="mt-2 text-sm text-red-500">
                        @foreach ($errors->get("urls.{$loop->index}.*") as $fields)
                            @foreach ($fields as $error)
                                {{ $error }}
                            @endforeach
                        @endforeach
                    </div>
                @endif
            @endforeach

            <x-hub::button theme="gray" wire:click.prevent="addUrl">
                {{ __('adminhub::partials.urls.create_btn') }}
            </x-hub::button>
        </div>
    </div>
</div>
