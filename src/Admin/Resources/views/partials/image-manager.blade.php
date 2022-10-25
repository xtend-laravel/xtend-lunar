<div class="rounded-t-xl bg-white shadow">
    <div class="flex-col space-y-4">

        <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <x-go-image-16 class="h-6 w-6 text-[#CFA55B]" />
            <span class="ml-2 text-sm font-semibold text-white">
                {{ __('adminhub::partials.image-manager.heading') }}
            </span>
            @if (!empty($chooseFrom))
                <div>
                    <x-hub::button theme="gray" type="button" wire:click="$set('showImageSelectModal', true)">
                        {{ __('adminhub::partials.image-manager.select_images_btn') }}
                    </x-hub::button>

                    <x-hub::modal.dialog wire:model="showImageSelectModal">
                        <x-slot name="title">
                            {{ __('adminhub::partials.image-manager.select_images') }}
                        </x-slot>
                        <x-slot name="content">
                            <div class="grid max-h-96 grid-cols-4 gap-4 overflow-y-auto">
                                @forelse($chooseFrom as $productImage)
                                    <label @class([
                                        'cursor-pointer' => !in_array($productImage->id, $this->currentImageIds),
                                        'opacity-50 cursor-not-allowed' => in_array(
                                            $productImage->id,
                                            $this->currentImageIds
                                        ),
                                    ])
                                        wire:key="product_image_{{ $productImage->id }}">
                                        <input wire:model="selectedImages" name="selectedImages"
                                            value="{{ $productImage->id }}" class="peer sr-only" type="checkbox"
                                            @if (in_array($productImage->id, $this->currentImageIds)) disabled @endif>
                                        <img src="{{ $productImage->getFullUrl('small') }}"
                                            class="rounded-lg border-2 border-transparent shadow-sm peer-checked:border-blue-500">
                                    </label>
                                @empty
                                    <div class="col-span-3">
                                        <x-hub::alert>{{ __('adminhub::notifications.product.no-images-associated') }}
                                        </x-hub::alert>
                                    </div>
                                @endforelse
                            </div>
                        </x-slot>
                        <x-slot name="footer">
                            <div class="flex justify-end space-x-4">
                                <x-hub::button type="button" theme="gray"
                                    wire:click="$set('showImageSelectModal', false)">{{ __('adminhub::global.cancel') }}
                                </x-hub::button>
                                <x-hub::button type="button" :disabled="!count($selectedImages)" wire:click.prevent="selectImages">
                                    {{ __('adminhub::partials.image-manager.select_images_btn') }}
                                </x-hub::button>
                            </div>
                        </x-slot>

                    </x-hub::modal.dialog>
                </div>
            @endif
        </header>

        <div class="p-6">
            <x-hub::input.fileupload wire:model="{{ $wireModel }}" :filetypes="$filetypes" multiple />
        </div>
        @if ($errors->has($wireModel . '*'))
            <x-hub::alert level="danger">{{ __('adminhub::partials.image-manager.generic_upload_error') }}
            </x-hub::alert>
        @endif

        <div class="p-6">
            <div wire:sort sort.options='{group: "images", method: "sort"}'
                class="relative mt-4 flex gap-x-8 space-y-2">
                @foreach ($this->images as $image)
                    <div class="flex items-center justify-between rounded-md border bg-white p-4 shadow-sm"
                        sort.item="images" sort.id="{{ $image['sort_key'] }}"
                        wire:key="image_{{ $image['sort_key'] }}">
                        <div class="flex items-center space-x-6">
                            @if (count($images) > 1)
                                <div class="cursor-move" sort.handle>
                                    <x-hub::icon ref="dots-vertical" style="solid" class="cursor-grab text-gray-400" />
                                </div>
                            @endif

                            <div>
                                <button type="button" wire:click="$set('images.{{ $loop->index }}.preview', true)">
                                    <img src="{{ $image['thumbnail'] }}" class="w-32 overflow-hidden rounded-md" />
                                </button>
                                <x-hub::modal wire:model="images.{{ $loop->index }}.preview">
                                    <img src="{{ $image['original'] }}">
                                </x-hub::modal>
                            </div>

                            <div class="w-full">
                                <x-hub::input.text wire:model="images.{{ $loop->index }}.caption"
                                    placeholder="Enter Alt. text" />
                            </div>

                            <div class="ml-4 flex items-center space-x-4">
                                <x-hub::tooltip text="Make primary">
                                    <x-hub::input.toggle :disabled="$image['primary']" :on="$image['primary']"
                                        wire:click.prevent="setPrimary('{{ $loop->index }}')" />
                                </x-hub::tooltip>

                                @if (!empty($image['id']))
                                    <x-hub::tooltip :text="__('adminhub::partials.image-manager.remake_transforms')">
                                        <button wire:click.prevent="regenerateConversions('{{ $image['id'] }}')"
                                            href="{{ $image['original'] }}" type="button">
                                            <x-hub::icon ref="refresh" style="solid"
                                                class="text-gray-400 hover:text-indigo-500 hover:underline" />
                                        </button>
                                    </x-hub::tooltip>
                                @endif

                                <button type="button" wire:click.prevent="removeImage('{{ $image['sort_key'] }}')"
                                    class="text-gray-400 hover:text-red-500">
                                    <x-hub::icon ref="trash" style="solid" />
                                </button>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
