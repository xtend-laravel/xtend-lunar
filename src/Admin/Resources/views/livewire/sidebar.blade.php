<div id="sidebar">
    <x-hub::menu handle="sidebar" current="{{ request()->route()->getName() }}">
        <x-xtend:hub::menu-list :sections="$component->sections" :items="$component->items" :active="$component->attributes->get('current')" menu-type="main_menu" />
    </x-hub::menu>

    @if (Auth::user()->can('settings'))
        <div class="absolute bottom-14 left-0 mt-4 flex w-full flex-col pt-4"
            :class="{ 'items-center': !showExpandedMenu }">
            <a href="{{ route('hub.settings') }}" @class([
                'text-white menu-link',
                'menu-link--active' => Str::contains(request()->url(), 'settings'),
                'menu-link--inactive' => !Str::contains(request()->url(), 'settings'),
            ]) :class="{ 'group': !showExpandedMenu }">
                {!! Lunar\Hub\LunarHub::icon('cog', 'w-5 h-5') !!}

                <span x-cloak x-show="showExpandedMenu" class="text-sm font-medium">
                    {{ __('adminhub::global.settings') }}
                </span>

                <span
                    class="invisible absolute left-full z-10 ml-4 w-28 rounded bg-gray-900 p-2 text-center text-xs text-white group-hover:visible dark:bg-gray-800">
                    {{ __('adminhub::global.settings') }}
                </span>
            </a>
        </div>
    @endif
</div>
