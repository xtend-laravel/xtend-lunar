<header class="nav-header hidden w-full bg-white lg:block">
    <div class="mx-auto flex h-16 justify-between">
        <div class="ml-6 flex items-center gap-x-4">
            <a href="#" class="text-gray-500 hover:text-black">
                <x-entypo-menu class="h-6 w-6" />
            </a>
            <a href="#" class="text-gray-500 hover:text-black">
                <x-grommet-vm-maintenance class="h-6 w-6" />
            </a>
            <a href="#" class="text-gray-500 hover:text-black">
                <x-icomoon-eye class="h-6 w-6" />
            </a>
        </div>
        <div class="lg:flex lg:items-center lg:justify-end">
            <div class="mt-3 px-4">
                @livewire('hub.components.switch-language')
            </div>
            <div class="mt-1 flex items-center">
                <button type="button">
                    <x-bx-help-circle class="h-6 w-6 text-black" />
                </button>
            </div>
            <div class="mt-1 flex items-center pl-3">
                <button type="button">
                    <x-bx-search class="h-6 w-6 text-black" />
                </button>
            </div>
            <div class="mt-3 flex items-center px-3">
                @livewire('system.notifications')
            </div>
            <div class="mx-4">
                @include('adminhub::partials.navigation.header-user-dropdown')
            </div>
            <div class="ml-4 flex h-16 items-center bg-[#222b39] px-4 pt-2 pr-3">
                @if (str(request()->route()->getName())->endsWith('.show'))
                    @livewire('hub.components.timeline')
                @else
                    @livewire('staff.notifications')
                @endif
            </div>
        </div>
    </div>
</header>
