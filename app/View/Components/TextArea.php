<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{   
    public $id;
    public $name;
    public $rows;
    public $placeholder;
    public $class;
    public $label;
    public $value;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $name, $rows = "4", $placeholder="", $class="", $label="", $value="")
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->label        = $label;
        $this->rows         = $rows;
        $this->placeholder  = $placeholder;
        $this->class        = $class;
        $this->value        = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.text-area');
    }
}
