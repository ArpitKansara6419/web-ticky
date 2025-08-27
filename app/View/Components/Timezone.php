<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class Timezone extends Component
{
    public $timezone;
    public $utc;
    public $flag;

    /**
     * Create a new component instance.
     */
    public function __construct($timezone)
    {
        $this->timezone = $timezone;

        $json = File::get(public_path('country.json'));

        $countries = collect(json_decode($json, true));

        $timezone_object = $countries->pluck('timezones')
            ->flatten(1)
            ->firstWhere('zoneName', $timezone);

        $this->flag = $countries->first(function ($country) use ($timezone) {
            return collect($country['timezones'])->contains('zoneName', $timezone);
        })['emoji'] ?? null;

        $this->utc = isset($timezone_object['gmtOffsetName'])? $timezone_object['gmtOffsetName'] : '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.timezone', [
            'utc' => $this->utc,
            'flag' => $this->flag
        ]);
    }
}
