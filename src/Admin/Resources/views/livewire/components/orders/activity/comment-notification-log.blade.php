<div>
    <div class="flex flex-col space-y-2">
        <div>{!! nl2br($log->getExtraProperty('content')) !!}</div>

        @if($this->isOwnLog)
            <x-hub::button size="xs" theme="gray" type="button" wire:click="$set('showConfirmationModal', true)">
                {{ __('Delete Comment') }}
            </x-hub::button>
        @endif
    </div>

    <x-hub::modal.dialog wire:model="showConfirmationModal">
        <x-slot name="title">
            {{ __('Delete comment') }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600">
                {{ __("Are you sure you want to update this comment?") }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-2">
                <x-hub::button type="button" wire:click.prevent="$set('showConfirmationModal', false)"
                               class="bg-gray-300 text-black hover:bg-gray-300 hover:text-black">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button type="button" wire:click.prevent="removeComment"
                               class="bg-[#CFA55B] text-white hover:bg-[#CFA55B]">
                    {{ __('Confirm') }}
                </x-hub::button>
            </div>
        </x-slot>
    </x-hub::modal.dialog>
</div>
