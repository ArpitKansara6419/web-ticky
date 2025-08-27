<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    /**
     * Create a new component instance.
     * 
     */

    public $type;
    public $label;
    public $class;


    public function __construct($type = 'info' , $label ="", $class="")
    {
        $this->type = $type;
        $this->label = $label;
        $this->class = $class;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.badge');
    }
}
