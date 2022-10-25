<div class="relative z-40"
     role="dialog"
     aria-modal="true">
    <div class="fixed inset-0 bg-gray-600/75"
         x-show="{{ $target }}"
         x-cloak
         aria-hidden="true"></div>

    <div {{ $attributes->merge(['class' => 'fixed inset-y-0 right-0 flex pl-10']) }}
         x-show="{{ $target }}"
         x-cloak
         x-transition:enter="transition ease-in-out duration-300 sm:duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 sm:duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
        <div class="w-full p-4 overflow-y-auto bg-white focus:outline-none"
             x-on:click.away="{{ $target }} = false">
            {{ $slot }}
        </div>
    </div>
</div>
