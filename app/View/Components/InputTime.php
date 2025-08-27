<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputTime extends Component
{
    // public $min;
    // public $max;
    public $value;
    public $label;
    public $name;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name='', $id='', $label='', $value = '')
    {   
        $this->name = $name;
        $this->id = $id;
        // $this->min = $min;
        // $this->max = $max;
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.input-time');
    }
}
