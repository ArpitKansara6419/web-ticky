<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusToggle extends Component
{   
    public $id;
    public $name;
    public $activeLabel;
    public $inactiveLabel;
    public $value;

    public function __construct($id = 'status', $name='status', $activeLabel = 'Active', $inactiveLabel = 'Inactive',  $value = 1)
    {
        $this->id               = $id;
        $this->name             = $name;
        $this->activeLabel      = $activeLabel;
        $this->inactiveLabel    = $inactiveLabel;
        $this->value            = (string)$value;
    }

    public function render()
    {
        return view('components.status-toggle');
    }
}
