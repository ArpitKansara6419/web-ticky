<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputDropdown extends Component
{
    public $name;
    public $label;
    public $id;
    public $placeholder;
    public $options;
    public $class;
    public $optionalLabel;
    public $optionalValue;
    public $optionalValue2;
    public $optionalValue3;
    public $optionalValue4;
    public $value;
    public $extraValue;


    /**
     * Create a new component instance.
     */
    public function __construct(
        $name, 
        $label = '', 
        $id = '', 
        $placeholder = '', 
        $options = [], 
        $class = '', 
        $optionalLabel = 'name', // Default label field
        $optionalValue = 'id', // Default value field
        $value = null ,
        $optionalValue2 = null,
        $optionalValue3 = null,
        $optionalValue4 = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->id = $id ?: $name; // If no ID is passed, use the name as ID
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->optionalLabel = $optionalLabel;
        $this->optionalValue = $optionalValue;
        $this->value = $value; // Assign value to property
        $this->optionalValue2 = $optionalValue2;
        $this->optionalValue3 = $optionalValue3;
        $this->optionalValue4 = $optionalValue4;

        if (is_string($options)) {
            $options = json_decode($options, true);  // Decode if passed as JSON string
        }
    
        // Ensure options is now an array
        $this->options = collect($options)->map(function ($option) {
            return [
                'name' => $option[$this->optionalLabel] ?? 'N/A',
                'value' => $option[$this->optionalValue] ?? null,
                'extra_value' => $option[$this->optionalValue2] ?? null,
                'extra_value2' => $option[$this->optionalValue3] ?? null,
                'extra_value3' => $option[$this->optionalValue4] ?? null
            ];
        })->toArray();

        if($id === 'add_timezone_id'){
            // dd($options[0][$this->optionalValue3]);
            // dd($this->options);
        }
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.input-dropdown');
    }
}
