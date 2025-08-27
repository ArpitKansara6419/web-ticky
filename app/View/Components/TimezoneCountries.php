<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class TimezoneCountries extends Component
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
        
        $json = File::get(public_path('country.json'));

        $countries = collect(json_decode($json, true));

        $this->countries = $countries->select('name')->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.timezone-countries', [
            'countries' => $this->countries
        ]);
    }
}
