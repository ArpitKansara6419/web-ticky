<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputNumber extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $value;
    public $class;

    /**
     * Create a new component instance.
     */
    public function __construct($id='', $name='', $label = '', $placeholder = '', $value = '', $class = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.input-number');
    }
}
