<?php

namespace App\View\Components;

use App\Models\Role;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoleDropdown extends Component
{
    public $roles;
    public $name;
    public $id;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name = '',  
        $id = '',
    )
    {
        $this->name = $name;
        $this->id = $id ?: $name;
        $this->roles = Role::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.role-dropdown', [
            'roles' => $this->roles
        ]);
    }
}
