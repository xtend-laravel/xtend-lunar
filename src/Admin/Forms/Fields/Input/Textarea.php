<?php

namespace Xtend\Extensions\Lunar\Admin\Forms\Fields\Input;

use Xtend\Extensions\Lunar\Admin\Forms\InputField;

class Textarea extends InputField
{
    /**
     * Whether or not the input has an error to show.
     *
     * @var bool
     */
    public bool $error = false;

    /**
     * Initialise the component.
     *
     * @param  bool  $error
     */
    public function __construct($error = false)
    {
        $this->error = $error;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('adminhub::components.input.textarea');
    }
}
