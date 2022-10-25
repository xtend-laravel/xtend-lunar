<?php

namespace Xtend\Extensions\Lunar\Admin\Views\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class MenuList extends Component
{
    /**
     * @param  \Illuminate\Support\Collection  $sections
     * @param  \Illuminate\Support\Collection  $items
     * @param  string  $active
     * @param  string  $menuType
     */
    public function __construct(
        public Collection $sections,
        public Collection $items,
        public string $active,
        public string $menuType = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('adminhub::components.menu-list');
    }
}
