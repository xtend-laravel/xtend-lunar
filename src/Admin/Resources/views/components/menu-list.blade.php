<div>
    @if (count($items))
        <div>
            <ul class="space-y-2">
                @foreach ($items as $item)
                    <li>
                        <a href="{{ route($item->route) }}" @class([
                            'text-white menu-link',
                            'menu-link--active' => $item->isActive($active),
                            'menu-link--inactive' => !$item->isActive($active),
                        ])>
                            {!! $item->renderIcon('shrink-0 w-5 h-5') !!}

                            <span class="text-sm font-medium">
                                {{ $item->name }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @forelse ($sections as $section)
        @if (count($section->getItems()))
            <div>
                <header class="section-header flex text-sm font-semibold uppercase text-white" x-cloak>
                    <span class="ml-1">{{ $section->name }}</span>
                </header>

                <ul class="section-items space-y-2">
                    @foreach ($section->getItems() as $item)
                        <li>
                            <a href="{{ route($item->route) }}" @class([
                                'flex items-center gap-2 p-2 rounded text-gray-500',
                                'text-[#CFA55B] hover:text-blue-600' => $item->isActive($active),
                                'text-white' => !$item->isActive($active) && $menuType === 'main_menu',
                            ])>
                                {!! $item->renderIcon('shrink-0 w-5 h-5') !!}

                                <span class="text-sm font-medium">
                                    {{ $item->name }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @empty
    @endforelse
</div>
