<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToastMessage extends Component
{
   
    public $type;
    public $message;
    public $icon;
    public $position;
    public $error;

     /**
     * Create a new component instance.
     */

    public function __construct( 
        $type = 'danger', 
        $message = '', 
        $icon = '', 
        $position = 'top-right',
        $error = ''
        )
    {
        $this->type     = $type;
        $this->message  = $message;
        $this->icon     = $icon;
        $this->position = $position;
        $this->error    = $error;
        }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.toast-message');
    }
}
