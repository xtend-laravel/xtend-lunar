<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? 'Hub' }} | {{ config('app.name') }}</title>

    <x-hub::branding.favicon />

    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;500;700;900&display=swap" rel="stylesheet">

    @livewireTableStyles

{{--    <link href="{{ asset('vendor/lunar/admin-hub/app.css?v=1') }}"--}}
{{--          rel="stylesheet">--}}

    @if ($viteSupport ?? false)
        @vite($vite ?? [])
    @endif

    @if ($styles = \Lunar\Hub\LunarHub::styles())
        @foreach ($styles as $asset)
            <link href="{!! $asset->url() !!}" rel="stylesheet">
        @endforeach
    @endif

    <style>
        .filepond--credits {
            display: none !important;
        }
    </style>

    <script>
        JSON.parse(localStorage.getItem('_x_showExpandedMenu')) ?
            document.documentElement.classList.add('app-sidemenu-expanded') :
            document.documentElement.classList.remove('app-sidemenu-expanded');

        document.addEventListener('alpine:init', () => {
            document.documentElement.classList.remove('app-sidemenu-expanded');
        })
    </script>

    @livewireStyles
    @stack('hub-styles')
</head>

<body class="h-full overflow-hidden bg-gray-50 bg-black/20 antialiased dark:bg-gray-900" :class="{ 'dark': darkMode }"
    x-data="{
        showExpandedMenu: $persist(false),
        showMobileMenu: false,
        darkMode: $persist(false),
    }">
    {!! \Lunar\Hub\LunarHub::paymentIcons() !!}

    <div class="flex h-full">
        @include('adminhub::partials.navigation.side-menu-mobile')

        @include('adminhub::partials.navigation.side-menu')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('adminhub::partials.navigation.header-mobile')

            <main class="flex flex-1 overflow-hidden">
                <section class="h-full min-w-0 flex-1 overflow-y-auto lg:order-last">
                    @include('adminhub::partials.navigation.header')

                    <div class="{{ config('lunar-hub.system.layout_width', 'max-w-screen-2xl') }} mx-auto p-12">
                        @yield('main', $slot)
                    </div>
                </section>

                @yield('menu')

                @if ($menu ?? false)
                    @include('adminhub::partials.navigation.side-menu-nested')
                @endif
            </main>
        </div>
    </div>

    <x-hub::notification />

    {{--@livewire('hub-license')--}}
    @livewire('system.real-time-notifications')
    @livewireScripts
    @stack('hub-scripts')

    @if ($scripts = \Lunar\Hub\LunarHub::scripts())
        @foreach ($scripts as $asset)
            <script src="{!! $asset->url() !!}"></script>
        @endforeach
    @endif

    <script src="{{ asset('vendor/lunar/admin-hub/app.js') }}"></script>
</body>

</html>
