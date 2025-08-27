<?php

namespace App\View\Components;

use App\Models\HolidaySync;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CountryDropDown extends Component
{
    public $countries;
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
        $this->countries = HolidaySync::select('country_name')
        ->distinct()
        ->get(); 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.country-drop-down', [
            'countries' => $this->countries
        ]);
    }
}
