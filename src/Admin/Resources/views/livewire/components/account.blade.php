<div class="mx-auto max-w-2xl">
    <div class="rounded bg-white p-4 shadow">
        <div class="flex items-center">
            <div><img class="inline-block h-10 w-10 rounded-full" src="{{ $staff->gravatar }}" alt=""></div>
            <div class="ml-4 grow">
                <x-hub::alert>
                    {{ __('adminhub::account.avatar_notice') }}
                </x-hub::alert>
            </div>
        </div>

        <div>
            @livewire('hub.components.forms.profile-form', ['model' => $staff])
        </div>
    </div>
</div>
