<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextField extends Component
{
    public  $id; // Input field ID
    public  $name; // Input field name
    public  $label; // Label for the input
    public  $placeholder; // Placeholder text
    public  $class; // Additional classes
    public  $value;
    public $attributes; 

    /**
     * Create a new component instance.
     */
    public function __construct($id, $name, $label=" ", $placeholder = '', $class = '', $value = '',  $attributes = [] )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->value = $value;
        $this->attributes = $attributes; 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.text-field');
    }
}
