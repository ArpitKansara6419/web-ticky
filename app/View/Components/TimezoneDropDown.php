<?php

namespace App\View\Components;

use App\Services\TimezoneService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TimezoneDropDown extends Component
{
    public $timezones;
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
        $this->timezones = app(TimezoneService::class)->getAllTimezones()->flatMap(function ($country) {
            return array_map(function ($tz) use ($country) {
                return array_merge($tz, [
                    'country_name' => $country['name'],
                    'phone_code' => $country['phone_code'],
                    'iso2' => $country['iso2'],
                    'emoji' => $country['emoji'],
                ]);
            }, $country['timezones'] ?? []);
        })
        ->unique('zoneName')
        ->values()
        ->all(); 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.timezone-drop-down', [
            'timezones' => $this->timezones
        ]);
    }
}
