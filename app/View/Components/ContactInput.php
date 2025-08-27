<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ContactInput extends Component
{
    public $id;
    public $name;
    public $label;
    public $selectedCountry;
    public $countries;
    public $placeholder;
    public $required; // Changed property name
    public $value;

    public function __construct($id, $name, $label = 'Contact', $selectedCountry = '+91', $countries = null, $placeholder = null, $required = false, $value = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->selectedCountry = $selectedCountry;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->required = $required; // Initialize the new property

        // Default countries if none are provided
        $this->countries = $countries ?? [
            '+91' => 'India (+91)',
            '+1'  => 'US (+1)',
            '+61' => 'Aus (+61)',
            '+44' => 'UK (+44)',
        ];
    }

    public function render()
    {
        return view('components.contact-input');
    }
}
