<div>
    <header class="flex items-center justify-between gap-1 rounded-t-xl bg-[#353F4F] p-3">
        <div class="flex items-center gap-1">
            <x-akar-info class="h-6 w-6 text-[#CFA55B]" />
            <span class="ml-1 text-sm font-semibold text-white">{{ __('Features') }}</span>
        </div>
    </header>
    <div class="p-4 bg-white">
        <div class="w-full overflow-x-auto">
            {{ $this->form }}
        </div>
    </div>
</div>
