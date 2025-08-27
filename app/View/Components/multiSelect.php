<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class multiSelect extends Component
{
    /**
     * Create a new component instance.
     */

    public $name;
    public $label;
    public $id;
    public $placeholder;
    public $options;
    public $class;
    public $optionalLabel;
    public $optionalValue;
    public $value;

    public function __construct(
        $name, 
        $label = '', 
        $id = '', 
        $placeholder = '', 
        $options = [], 
        $class = '', 
        $optionalLabel = 'name', 
        $optionalValue = 'id',
        $value = null 
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->id = $id ?: $name; 
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->optionalLabel = $optionalLabel;
        $this->optionalValue = $optionalValue;
        $this->value = $value;

        if (is_string($options)) {
            $options = json_decode($options, true);  // Decode if passed as JSON string
        }

        $this->options = collect($options)->map(function ($option) {
            return [
                'name' => $option[$this->optionalLabel] ?? 'N/A',
                'value' => $option[$this->optionalValue] ?? null
            ];
        })->toArray();
    }

 

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.multi-select');
    }
}
