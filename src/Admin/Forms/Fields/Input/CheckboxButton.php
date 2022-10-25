<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Fields\Input;

use Xtend\Extensions\Lunar\Admin\Forms\InputField;

class CheckboxButton extends InputField
{
    /**
     * Whether the toggle should be in an on state.
     *
     * @var bool
     */
    public $on = false;

    /**
     * Whether the toggle should be disabled.
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * Create the component instance.
     *
     * @param  bool  $on
     * @param  bool  $disabled
     */
    public function __construct($on = false, $disabled = false)
    {
        $this->on = $on;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('adminhub::components.input.checkbox-button');
    }
}
