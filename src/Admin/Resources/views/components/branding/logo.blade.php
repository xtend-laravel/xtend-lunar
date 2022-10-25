@if (!$iconOnly)
    <img src="{{ asset('img/jl-logo.png') }}" {{ $attributes }} alt="Lunar Logo" class="w-auto p-4" x-cloak />
@else
    <img src="{{ asset('img/favicon.png') }}" {{ $attributes }} alt="Lunar Logo" class="mx-auto h-8 w-8" />
@endif
