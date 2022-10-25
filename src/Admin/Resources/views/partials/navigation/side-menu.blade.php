<div class="lg:flex lg:flex-shrink-0 hidden">
    <div class="bg-white relative flex flex-col justify-between"
        :class="{
            'w-64': showExpandedMenu,
            'w-20': !showExpandedMenu
        }"
        data-ref="layout-sidemenu">
        <div class="flex-1">
            <a href="{{ route('hub.index') }}" class="w-full px-4 flex items-center">
                <x-hub::branding.logo x-show="showExpandedMenu" />
                <x-hub::branding.logo x-show="!showExpandedMenu" iconOnly />
            </a>

            <div class="w-full pt-4">
                @livewire('sidebar')
            </div>
        </div>

        <button x-on:click="showExpandedMenu = !showExpandedMenu"
            class="bottom-16 z-50 rounded border border-gray-100 bg-white p-1 text-gray-600 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300 absolute -right-[13px]">
            <span :class="{ '-rotate-180': showExpandedMenu }" class="block">
                <x-hub::icon ref="chevron-right" class="h-4 w-4" style="solid" />
            </span>
        </button>
    </div>
</div>
