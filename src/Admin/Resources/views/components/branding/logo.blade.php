@if (!$iconOnly)
    <img src="{{ Vite::asset('resources/images/jl-logo.png') }}" {{ $attributes }} alt="Lunar Logo" class="w-auto p-4" x-cloak />
@else
    <img src="{{ Vite::asset('resources/images/favicon.png') }}" {{ $attributes }} alt="Lunar Logo" class="mx-auto h-8 w-8" />
@endif
